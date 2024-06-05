<?php

namespace App\Actions\Inventory;

use Illuminate\Support\Facades\DB;

class GetInventoryStockAction
{
    private $inventoryId;

    public function __construct(int $inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function run($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $itemBalances = DB::table('inventory_ledger_items')
            ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
            ->join(DB::raw('(SELECT * FROM item_prices WHERE (item_id, created_at) IN
                (SELECT item_id, MAX(created_at)
                FROM item_prices
                GROUP BY item_id)) as latest_prices'),
                'items.id', '=', 'latest_prices.item_id')
            ->join('uoms as item_uom', 'latest_prices.uom_id', '=', 'item_uom.id')
            ->join('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
            ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            ->join('uom_conversions', function ($join) {
                $join->on('latest_prices.uom_id', '=', 'uom_conversions.base_unit_id')
                    ->on('items.base_uom_id', '=', 'uom_conversions.conversion_unit_id')
                    ->where('uom_conversions.is_active', 1);
            })
            ->select(
                'items.name',
                'inventory_ledger_items.item_id', // Prefix the table name here
                'latest_prices.uom_id as item_uom_id',
                'latest_prices.price',
                'items.base_uom_id  as base_unit_id',
                'item_uom.name as base_uom_name',
                'base_uom.name as conversion_uom_name',
                'uom_conversions.conversion',
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) -
                  SUM(CASE WHEN action = "out" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END)) as opening_balance'),
                DB::raw('SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as in_balance'),
                DB::raw('SUM(CASE WHEN action = "out" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as out_balance'),
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
                  SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
                  SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) as closing_balance'),
                DB::raw('((latest_prices.price / uom_conversions.conversion) *
                  (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
                  SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
                  SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END))) as total_value')
            )
            ->where('inventory_id', $this->inventoryId)
               ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(inventory_ledgers.created_at)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date) {
                $q->whereDate('inventory_ledgers.created_at', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date) {
                $q->whereBetween('inventory_ledgers.created_at', [now(), $to_date]);
            })
            ->groupBy('inventory_ledger_items.item_id', 'items.name', 'latest_prices.price', 'latest_prices.uom_id', 'items.base_uom_id', 'uom_conversions.conversion', 'item_uom.name', 'base_uom.name');
        $result = $itemBalances->get();

        return $result;
    }
}
