<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Item;

class TestController extends Controller
{
    //
    public function index()
    {
        $inventory = Inventory::find(2);
        $inventoryLedgers = $inventory->inventory_ledgers()->whereBetween("date", [CurrentDate() . " 00:00:00", CurrentDate() . " 23:59:59"])
        ->with("ledgerable.items")->get();
        $purchaseLedgers = collect();
        $saleLedgers = collect();
        foreach($inventoryLedgers as $inventoryLedger){
            if($inventoryLedger->ledgerable_type == "purchase_order"){
                $purchaseLedgers->push($inventoryLedger->ledgerable);
            }
            if($inventoryLedger->ledgerable_type == "sale"){
                $saleLedgers->push($inventoryLedger->ledgerable);
            }
        }

        $purchaseItems = collect();
        foreach($purchaseLedgers as $purchaseLedger){
            foreach($purchaseLedger->items as $item){
                $purchaseItems->push($item);
            }
        }

        foreach($saleLedgers as $saleLedger){

        }

        $inItems = $purchaseItems->groupBy('item_id')
        ->map(function ($group) {
            return [
                'item_id' => $group->first()['item_id'],
                'item' => Item::find($group->first()['item_id']),
                'quantity' => $group->sum('quantity'),
            ];
        })->values();

        $data["entry_items"] = $inItems;
        $data["expense_items"] = [];

        ResponseData($data);
    }
}
