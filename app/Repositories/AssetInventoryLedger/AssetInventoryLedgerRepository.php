<?php

namespace App\Repositories\AssetInventoryLedger;

use Illuminate\Support\Facades\DB;

class AssetInventoryLedgerRepository implements AssetInventoryLedgerInterface
{
    public function list($request){
        // return $request->all();
        $allInventories = DB::table('inventories')->pluck('name');

        $inventoryData = DB::table('asset_inventory_ledgers')
        ->join('asset_items', 'asset_inventory_ledgers.asset_item_id', '=', 'asset_items.id')
        ->join('inventories', 'asset_inventory_ledgers.inventory_id', '=', 'inventories.id')
        ->select(
            'asset_items.id as asset_item_id',
            'asset_items.name as asset_item_name',
            'asset_items.item_code as item_code', // Assuming you have item_code in asset_items
            'inventories.name as inventory_name',
            DB::raw('SUM(quantity) as total_quantity')
        )
        ->groupBy('asset_items.id', 'asset_items.name', 'asset_items.item_code', 'inventories.name')
        ->get();
        // return $inventoryData;
        $results = [];
        foreach ($inventoryData as $data) {
            if (!isset($results[$data->asset_item_id])) {
                $results[$data->asset_item_id] = [
                    'item_code' => $data->item_code,
                    'item_name' => $data->asset_item_name,
                    'total' => 0,
                ];
                // Initialize all inventories with 0
                foreach ($allInventories as $inventoryName) {
                    $results[$data->asset_item_id][$inventoryName] = 0;
                }
            }
            $results[$data->asset_item_id][$data->inventory_name] = $data->total_quantity;
            $results[$data->asset_item_id]['total'] += $data->total_quantity;
        }
        return $results;
    }
}