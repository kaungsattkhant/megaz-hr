<?php

namespace App\Repositories\Pack;

use App\Http\Action\Inventory\StoreInventory;
use App\Models\InventoryLedgerItem;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MenuStepItem;
use App\Models\Pack;
use App\Models\PackItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackRepository implements PackRepositoryInterface
{
    public function listAllData(Request $request, )
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
            $menu = Menu::find($data['menu_id']);
            $menuId = $data['menu_id'];
            $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
                ->whereHas('menuStep', function ($q) use ($menuId) {
                    $q->where('menu_id', $menuId)
                    ->where('type','ready_to_sale');
                })
                ->select('items.uom_id', DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'), 'menu_step_items.item_id')
                ->groupBy('item_id')
                ->get();
            // $menuItems = $menu->items;
            for ($i = 0; $i < $data['quantity']; $i++) {
                $pack = Pack::create([
                    'menu_id' => $data['menu_id'],
                    'date' => CurrentTime(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'ready',
                ]);
                $inventoryId = UserData()->department->inventory->inventory_id;
                $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($pack, 'pack', 'out');
                foreach ($menuStepItemByMenu as $item) {
                    // $itemInventories = InventoryLedgerItem::where('item_id', $item->id)->get();
                    // $enterInventoryValue = 0;
                    // $outInventroyValue = 0;

                    // foreach ($itemInventories as $itemInventory) {
                    //     if ($itemInventory->inventory_ledger->action == 'in') {
                    //         $enterInventoryValue += $itemInventory->quantity;
                    //     } else if ($itemInventory->inventory_ledger->action == 'out') {
                    //         $outInventroyValue += $itemInventory->quantity;
                    //     }
                    // }
                    // $stockInInventory = $enterInventoryValue - $outInventroyValue;
                    $itemInventory = InventoryLedgerItem::where('item_id', $item->item_id)
                        ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                        ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                        ->where('inventory_ledgers.inventory_id', $inventoryId)
                        ->first();
                    $stockInInventory = $itemInventory->in_stock_quantity ?? 0;
                    if ($stockInInventory < $item->total_quantity) {
                        ResponseMessage('Stock is not enough', 422);
                    }
                    $packItem = PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'quantity' => $item->total_quantity
                    ]);
                    $a = $inventoryLedger->inventory_ledger_items()->create([
                        'item_id' => $item->item_id,
                        'quantity' => $item->total_quantity,
                        'inventory_ledger_id' => $inventoryLedger->id,
                    ]);
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
