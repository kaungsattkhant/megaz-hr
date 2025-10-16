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
        $inventoryId = !isset($request->inventory_id) ? null : $request->inventory_id;
        $inventoryId = $request->inventory_id ?? null;
        $this->matchInventoryWithPack();
        $query = Pack::with(['menu', 'pack_items'])
            ->when($request->menu_id, fn($q) => $q->where('menu_id', $request->menu_id))
            ->where('inventory_id', $inventoryId);

        return ($request->per_page || $request->page)
            ? $query->paginate(config('common.list_count'))
            : $query->get();
    }


    public function matchInventoryWithPack()
    {
        DB::beginTransaction();
        try {
            $packAll = Pack::with('inventory_ledgers')->whereNull('inventory_id')->get();
            foreach ($packAll as $pack) {
                if ($pack->inventory_ledgers->isNotEmpty()) {
                    foreach ($pack->inventory_ledgers as $ledger) {
                        $pack->inventory_id = $ledger->inventory_id;
                        $pack->save();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }

    public function createPack(array $data)
    {
        return $this->optimizePackCreate($data);
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
            $this->orderService->checkInventoryEnough($menuId, $inventoryId, $quantity);

            $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
                ->whereHas('menuStep', function ($q) use ($menuId) {
                    $q->where('menu_id', $menuId)
                        ->where('type', 'ready_to_sale');
                })
                ->select('items.uom_id', 'items.name', DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'), 'menu_step_items.item_id')
                ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
                ->get();
            $pack_arrs = [];
            for ($i = 0; $i < $data['quantity']; $i++) {
                $pack = Pack::create([
                    'menu_id' => $data['menu_id'],
                    'date' => CurrentTime(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'ready',
                ]);
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

                        $ledgerItem = $inventoryLedger->inventory_ledger_items()->create([
                            'item_id' => $item->item_id,
                            'quantity' => $quantityToTake,
                            'inventory_ledger_id' => $inventoryLedger->id,
                        ]);
                        $remainingQuantity -= $quantityToTake;
                        $array[] = $quantityToTake;
                    }


                }
            }
            DB::commit();
            return ResponseMessage("Packing successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }

    public function optimizePackCreate($data)
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
            $this->orderService->checkInventoryEnough($menuId, $inventoryId, $quantity);

            // Get items required for the menu
            $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
                ->whereHas('menuStep', function ($q) use ($menuId) {
                    $q->where('menu_id', $menuId)
                        ->where('type', 'ready_to_sale');
                })
                ->select(
                    'items.uom_id',
                    'items.name',
                    DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'),
                    'menu_step_items.item_id'
                )
                ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
                ->get();

            // Get current inventory batches grouped by item_id
            $itemIds = $menuStepItemByMenu->pluck('item_id');
            $inventoryData = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->whereIn('inventory_ledger_items.item_id', $itemIds)
                ->select(
                    'inventory_ledger_items.item_id',
                    'inventory_ledgers.batch_no',
                    DB::raw("SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) -
                 SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity")
                )
                ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                ->orderBy('inventory_ledgers.created_at', 'asc')
                ->havingRaw("in_stock_quantity > 0")  // <-- filter only positive stock
                ->get()
                ->groupBy('item_id');


            // Create packs and consume batches
            for ($i = 0; $i < $quantity; $i++) {
                $pack = Pack::create([
                    'menu_id' => $menuId,
                    'date' => now(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'ready',
                    'inventory_id' => $inventoryId,
                ]);

                foreach ($menuStepItemByMenu as $item) {
                    PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'quantity' => $item->total_quantity
                    ]);

                    $batches = $inventoryData->get($item->item_id, collect());
                    if ($batches->isEmpty()) {
                        ResponseMessage("Not enough stock for item {$item->name}", 419);
                    }

                    $remainingQuantity = $item->total_quantity;

                    foreach ($batches as $batch) {
                        if ($remainingQuantity <= 0)
                            break;

                        $availableQty = (float) $batch->in_stock_quantity;
                        if ($availableQty <= 0)
                            continue;

                        $quantityToTake = min($remainingQuantity, $availableQty);

                        // Create ledger for this batch
                        $inventoryLedger = InventoryLedger::create([
                            'batch_no' => $batch->batch_no,
                            'date' => now(),
                            'ledgerable_id' => $pack->id,
                            'ledgerable_type' => 'pack',
                            'inventory_id' => $inventoryId,
                            'action' => 'out',
                        ]);

                        $inventoryLedger->inventory_ledger_items()->create([
                            'item_id' => $item->item_id,
                            'quantity' => $quantityToTake,
                            'inventory_ledger_id' => $inventoryLedger->id,
                        ]);

                        $remainingQuantity -= $quantityToTake;

                        // Reduce in_stock_quantity in memory to avoid negative stock in next iterations
                        $batch->in_stock_quantity -= $quantityToTake;
                    }

                    if ($remainingQuantity > 0) {
                        ResponseMessage("Not enough stock to fulfill pack for item {$item->name}", 419);
                    }
                }
            }

            // dd('end');
            DB::commit();
            ResponseMessage("Packing successfully");
        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }


    }

    public function changePack($request)
    {
        dd($request->all());
    }

}
