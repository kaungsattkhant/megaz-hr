<?php

namespace App\Http\Action\Inventory;

use Illuminate\Support\Facades\DB;

class InventoryLedger
{

    private $inventoryId;

    public function __construct(int $inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }
    public function isEnoughQuantityByItem($item_id, $quantity)
    {
        // $balanceQuantity = InventoryLedgerItem::where('item_id', $item_id)
        $balanceQuantity = DB::table('inventory_ledger_items')
            ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
            ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            ->where('inventory_ledger_items.item_id', $item_id)
            ->where('inventory_ledgers.inventory_id', $this->inventoryId)
            // ->select(DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" THEN inventory_ledger_items.quantity ELSE 0 END) as quantity')
            // )
                 ->select(DB::raw('
                SUM(CASE WHEN inventory_ledgers.action = "in" THEN quantity ELSE 0 END) -
                SUM(CASE WHEN inventory_ledgers.action = "out" THEN quantity ELSE 0 END) as quantity
            '))
            // ->where('inventory_ledgers.action', 'in')
            // ->sum('inventory_ledger_items.quantity');
            ->first();
       
        $ledgerQuantity = $balanceQuantity ? $balanceQuantity->quantity : 0;
        if ($ledgerQuantity < (int)$quantity) {
            ResponseMessage('Quantity is not enought', 419);
        }
        return true;
    }

}
