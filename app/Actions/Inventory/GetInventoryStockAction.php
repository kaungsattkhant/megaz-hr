<?php

namespace App\Actions\Inventory;

use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;

class GetInventoryStockAction
{
    private $inventoryId;

    public function __construct(int $inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function run()
    {

        // $purchaseItems = collect();
        // $purchaseLedgers = InventoryLedger::where("inventory_id", $this->inventoryId)
        // ->where("ledgerable_type", "purchase_order")
        // ->whereBetween("date", [CurrentDate() . " 00:00:00", CurrentDate() . " 23:59:59"])
        // ->with("inventory_ledger_items.item")->get();

        // foreach($purchaseLedgers as $purchaseLedger){
        //     foreach($purchaseLedger->inventory_ledger_items as $item){
        //         $purchaseItems->push($item);
        //     }
        // }
        // $incomingItems = $purchaseItems->groupBy("item_id")
        // ->map(function($group){
        //     return [
        //         'type' => 'incoming',
        //         'item_id' => $group->first()['item_id'],
        //         'quantity' => $group->sum('quantity'),
        //         'item' => $group->first()['item']
        //     ];
        // })->values();

        // $saleItems = collect();
        // $saleLedgers = InventoryLedger::where("inventory_id", $this->inventoryId)
        // ->where("ledgerable_type", "sale")
        // ->whereBetween("date", [CurrentDate() . " 00:00:00", CurrentDate() . " 23:59:59"])
        // ->with("inventory_ledger_items.item")->get();

        // foreach($saleLedgers as $saleLedger){
        //     foreach($saleLedger->inventory_ledger_items as $item){
        //         $saleItems->push($item);
        //     }
        // }

        // $outgoingItems = $saleItems->groupBy("item_id")
        // ->map(function($group){
        //     return [
        //         'type' => 'outgoing',
        //         'item_id' => $group->first()['item_id'],
        //         'quantity' => $group->sum('quantity'),
        //         'item' => $group->first()['item']
        //     ];
        // })->values();

        // $mergedItems = $incomingItems->concat($outgoingItems);
        // // $items = $mergedItems->groupBy("item_id")->values();
        // // return $items;

        // // previous records

        // $previousPurchaseItems = collect();
        // $previousPurchaseLedgers = InventoryLedger::where("inventory_id", $this->inventoryId)
        // ->where("ledgerable_type", "purchase_order")
        // ->where("date", "<", CurrentDate() . " 00:00:00")
        // ->with("inventory_ledger_items.item")->get();

        // foreach($previousPurchaseLedgers as $purchaseLedger){
        //     foreach($purchaseLedger->inventory_ledger_items as $item){
        //         $previousPurchaseItems->push($item);
        //     }
        // }

        // $previousIncomingItems = $previousPurchaseItems->groupBy("item_id")
        // ->map(function($group){
        //     return [
        //         'type' => 'previous incoming',
        //         'item_id' => $group->first()['item_id'],
        //         'quantity' => $group->sum('quantity'),
        //         'item' => $group->first()['item']
        //     ];
        // })->values();

        // $previousSaleItems = collect();
        // $previousSaleLedgers = InventoryLedger::where("inventory_id", $this->inventoryId)
        // ->where("ledgerable_type", "sale")
        // ->where("date", "<", CurrentDate() . " 00:00:00")
        // ->with("inventory_ledger_items.item")->get();
        // foreach($previousSaleLedgers as $saleLedger){
        //     foreach($saleLedger->inventory_ledger_items as $item){
        //         $previousSaleItems->push($item);
        //     }
        // }
        // $previousOutgoingItems = $previousSaleItems->groupBy("item_id")
        // ->map(function($group){
        //     return [
        //         'type' => 'previous outgoing',
        //         'item_id' => $group->first()['item_id'],
        //         'quantity' => $group->sum('quantity'),
        //         'item' => $group->first()['item']
        //     ];
        // })->values();
        // $mergedPreviousItems = $previousIncomingItems->concat($previousOutgoingItems);
        // $mergedAllItems = $mergedPreviousItems->concat($mergedItems);
        // $items = $mergedAllItems->groupBy("item_id")->values();
        // return $items;

        $itemBalances = DB::table('inventory_ledger_items')
            ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
            ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            ->select(
                'items.name',
                'item_id',
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) - 
                  SUM(CASE WHEN action = "out" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END)) as opening_balance'),
                DB::raw('SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as in_balance'),
                DB::raw('SUM(CASE WHEN action = "out" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as out_balance'),
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) + 
                  SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) - 
                  SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) as closing_balance')
            )
            ->where('inventory_id',$this->inventoryId)
            ->groupBy('item_id');
        $result = $itemBalances->get();

        return $result;
    }
}
