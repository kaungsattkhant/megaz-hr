<?php

namespace App\Repositories\Pack;

use App\Models\Item;
use App\Models\Menu;
use App\Models\Pack;
use App\Models\PackItem;
use App\Models\MenuStepItem;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Http\Action\Inventory\StoreInventory;

class PackRepository implements PackRepositoryInterface
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function listAllData(Request $request)
    {
        if ($request->menu_id !== null) {
            $query = Pack::with('menu')->where('menu_id', $request->menu_id);
        } else {
            $query = Pack::with('menu');
        }

        if ($request->per_page || $request->page) {
            $totalCount = $query->count();
            $pageNumber = $request->page ?? 1;
            $perPage = $request->per_page ?? 20;
            $skip = ($pageNumber - 1) * $perPage;
            $packs = $query->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'packs');
            $paginationData['packs'] = $packs;

            return $paginationData;
        } else {
            $packs = $query->get();
            return $packs;
        }
    }



    public function createPack(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $inventoryId = $data['inventory_id'];
            $quantity = $data['quantity'];
            $menu = Menu::where('is_active', 1)->find($data['menu_id']);
            if (!$menu) {
                ResponseMessage('Menu is invalid', 419);
            }
            $menuId = $data['menu_id'];

            $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
                ->whereHas('menuStep', function ($q) use ($menuId) {
                    $q->where('menu_id', $menuId)
                        ->where('type', 'ready_to_sale');
                })
                ->select('items.uom_id', 'items.name', DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'), 'menu_step_items.item_id')
                ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
                ->get();
                $pack_arrs=[];
            for ($i = 0; $i < $data['quantity']; $i++) {
                $pack = Pack::create([
                    'menu_id' => $data['menu_id'],
                    'date' => CurrentTime(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'ready',
                ]);
                $pack_arrs[]=$pack;
                $pack->inventory_id = $inventoryId;
                $this->orderService->checkInventoryEnough($menuId, $inventoryId, $quantity);
                foreach ($menuStepItemByMenu as $item) {
                    PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'quantity' => $item->total_quantity
                    ]);
                    $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id', $item->item_id)
                        ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                        ->where('inventory_ledgers.inventory_id', $inventoryId)
                        ->select(
                            'inventory_ledger_items.item_id',
                            'inventory_ledgers.batch_no'
                        )
                        ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                        ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                        ->orderBy('inventory_ledgers.created_at', 'asc')
                        ->get();
                    $totalOutQuantity = $item->total_quantity; // e.g. 15000
                    $remainingQuantity = $totalOutQuantity;
                    $array = [];
                    foreach ($inventoryItems as $inventory_item) {
                        if ($remainingQuantity <= 0) {
                            break;
                        }

                        $availableQty = (float) $inventory_item->in_stock_quantity;

                        // Determine how much to take from this batch
                        if ($availableQty <= 0) {
                            continue;
                        }

                        $quantityToTake = min($remainingQuantity, $availableQty);

                        $inventoryLedger = InventoryLedger::create([
                            'batch_no' => $inventory_item->batch_no,
                            'date' => now(),
                            'ledgerable_id' => $pack->id,
                            'ledgerable_type' => 'pack',
                            'inventory_id' => $inventoryId,
                            'action' => 'out',//out
                        ]);
                        // $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($orderItem, $morphMapName, $action);
                        $inventoryLedger->inventory_ledger_items()->create([
                            'item_id' => $item->item_id,
                            'quantity' => $quantityToTake,
                            'inventory_ledger_id' => $inventoryLedger->id,
                        ]);
                        $remainingQuantity -= $quantityToTake;
                        $array[] = $quantityToTake;
                    }

                    // $inventoryLedger->inventory_ledger_items()->create([
                    //     'item_id' => $item->item_id,
                    //     'quantity' => $item->total_quantity,
                    //     'inventory_ledger_id' => $inventoryLedger->id,
                    // ]);
                }
            }
            DB::commit();
            return ResponseMessage("Packing successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }
}
