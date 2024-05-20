<?php

namespace App\Repositories\Pack;

use App\Http\Action\Inventory\StoreInventory;
use App\Models\InventoryLedgerItem;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Pack;
use App\Models\PackItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackRepository implements PackRepositoryInterface
{
    public function createPack(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $menu = Menu::find($data['menu_id']);
            $menuItems = $menu->items;

             for ($i = 0; $i < $data['quantity']; $i++) {
                $pack = Pack::create([
                    'menu_id' => $data['menu_id'],
                    'date' => CurrentTime(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'not yet'
                ]);
                $inventoryId = UserData()->department->inventory->inventory_id;
                $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($pack, 'pack', 'out');

                foreach ($menuItems as $item) {
                    $itemInventories = InventoryLedgerItem::where('item_id', $item->id)->get();
                    $enterInventoryValue = 0;
                    $outInventroyValue = 0;

                    foreach ($itemInventories as $itemInventory) {
                        if ($itemInventory->inventory_ledger->action == 'in') {
                            $enterInventoryValue += $itemInventory->quantity;
                        } else if ($itemInventory->inventory_ledger->action == 'out') {
                            $outInventroyValue += $itemInventory->quantity;
                        }
                    }
                    $stockInInventory = $enterInventoryValue - $outInventroyValue;
                    if($stockInInventory < $item->pivot->weight)
                    {
                        ResponseMessage('Stock is not enough',402);
                    }
                    $packItem = PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->id,
                        'uom_id' => $item->pivot->uom_id,
                        'quantity' => $item->pivot->weight
                    ]);

                    $inventoryLedger->inventory_ledger_items()->create([
                        'item_id' => $item->id,
                        'quantity' => $item->pivot->weight,
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
