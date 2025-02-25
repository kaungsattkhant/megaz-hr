<?php

namespace App\Actions\Inventory;

use App\Models\Item;
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
        return $this->test($request);
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);

        $itemBalances = DB::table('inventory_ledger_items')
            ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
            ->join(DB::raw('(SELECT si.item_id, 
                                CAST(AVG(ip.price) AS DECIMAL(10,2)) AS average_price,
                                ip.base_uom_id
                        FROM supplier_items si
                        JOIN item_prices ip ON si.id = ip.supplier_item_id
                        WHERE ip.id IN (
                            SELECT MAX(sub_ip.id)
                            FROM item_prices sub_ip
                            WHERE sub_ip.supplier_item_id = si.id
                            GROUP BY sub_ip.supplier_item_id
                        )
                        GROUP BY si.item_id, ip.base_uom_id
        ) as latest_prices'), 'items.id', '=', 'latest_prices.item_id')
            ->join('uoms as item_uom', 'items.uom_id', '=', 'item_uom.id')
            ->join('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
            ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            ->join('uom_conversions', function ($join) {
                $join->on('latest_prices.base_uom_id', '=', 'uom_conversions.base_unit_id')
                    ->on('items.uom_id', '=', 'uom_conversions.conversion_unit_id')
                    ->where('uom_conversions.is_active', 1);
            })
            ->select(
                'items.name',
                'inventory_ledger_items.item_id',
                'items.uom_id as item_uom_id',
                'latest_prices.base_uom_id as base_uom_id',
                'latest_prices.average_price as price',
                'items.base_uom_id as base_unit_id',
                'item_uom.name as conversion_uom_name',
                'base_uom.name as  base_uom_name',
                'uom_conversions.conversion',
                'uom_conversions.id as conversion_unit_id',
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) -
                    SUM(CASE WHEN action = "out" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END)) as opening_balance'),
                DB::raw('SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as in_balance'),
                DB::raw('SUM(CASE WHEN action = "out" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as out_balance'),
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
                    SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
                    SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) as closing_balance'),
                DB::raw('((latest_prices.average_price / uom_conversions.conversion) *
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
                $q->whereDate('inventory_ledgers.created_at', '<=', $to_date);
            })
            ->groupBy(
                'inventory_ledger_items.item_id',
                'items.name',
                'items.uom_id',
                'latest_prices.average_price',
                'latest_prices.base_uom_id',
                'items.base_uom_id',
                'uom_conversions.conversion',
                'uom_conversions.id',
                'item_uom.name',
                'base_uom.name'
            )
            ->orderByRaw('CASE WHEN 
            (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
            SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
            SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) = 0 
            AND 
            (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
            SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
            SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) = 0
            THEN 0
            ELSE 1 
        END ASC')
            ->orderByRaw('CASE WHEN (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
        SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
        SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) = 0 
        THEN 0 
        ELSE 1 END ASC')
            ->orderBy('closing_balance', 'ASC')
            ->orderBy('total_value', 'ASC');

        $result = $itemBalances->get();
        return $result;
    }

    public function test($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $itemBalances = DB::table('inventory_ledger_items')
            ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
            ->leftJoin('inventory_items', function ($join) {
                $join->on('inventory_ledger_items.item_id', '=', 'inventory_items.item_id');
            })
            ->join(DB::raw('(SELECT si.item_id, 
                            CAST(AVG(ip.price) AS DECIMAL(10,2)) AS average_price                          
                    FROM supplier_items si
                    JOIN item_prices ip ON si.id = ip.supplier_item_id
                    WHERE ip.id IN (
                        SELECT MAX(sub_ip.id)
                        FROM item_prices sub_ip
                        WHERE sub_ip.supplier_item_id = si.id
                        GROUP BY sub_ip.supplier_item_id
                    )
                    GROUP BY si.item_id
    ) as latest_prices'), 'items.id', '=', 'latest_prices.item_id')
            ->join('uoms as item_uom', 'items.uom_id', '=', 'item_uom.id')
            ->join('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
            ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            ->join('uom_conversions', function ($join) {
                $join->on('items.base_uom_id', '=', 'uom_conversions.base_unit_id')
                    ->on('items.uom_id', '=', 'uom_conversions.conversion_unit_id')
                    ->where('uom_conversions.is_active', 1);
            })
            ->select(
                'items.name',
                'inventory_ledger_items.item_id',
                'items.uom_id as item_uom_id',
                'inventory_ledgers.inventory_id',
                'inventory_items.min_quantity',
                'latest_prices.average_price as price',
                'items.base_uom_id as base_unit_id',
                'item_uom.name as conversion_uom_name',
                'base_uom.name as  base_uom_name',
                'uom_conversions.conversion',
                'uom_conversions.id as conversion_unit_id',
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) -
                SUM(CASE WHEN action = "out" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END)) as opening_balance'),
                DB::raw('SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as in_balance'),
                DB::raw('SUM(CASE WHEN action = "out" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) as out_balance'),
                DB::raw('(SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
                SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
                SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) as closing_balance'),
                DB::raw('((latest_prices.average_price / uom_conversions.conversion) *
                (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
                SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
                SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END))) as total_value')
            )
            ->where('inventory_ledgers.inventory_id', $this->inventoryId)
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(inventory_ledgers.created_at)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date) {
                $q->whereDate('inventory_ledgers.created_at', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date) {
                $q->whereDate('inventory_ledgers.created_at', '<=', $to_date);
            })
            ->groupBy(
                'inventory_ledger_items.item_id',
                'items.name',
                'items.uom_id',
                'latest_prices.average_price',
                'inventory_items.min_quantity',
                'items.base_uom_id',
                'uom_conversions.conversion',
                'uom_conversions.id',
                'item_uom.name',
                'base_uom.name',
                'inventory_ledgers.inventory_id',
            )
            ->orderByRaw('CASE WHEN 
        (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
        SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
        SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) = 0 
        AND 
        (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
        SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
        SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) = 0
        THEN 0
        ELSE 1 
    END ASC')
            ->orderByRaw('CASE WHEN (SUM(CASE WHEN action = "in" AND DATE(date) < CURDATE() THEN quantity ELSE 0 END) +
    SUM(CASE WHEN action = "in" AND DATE(date) = CURDATE() THEN quantity ELSE 0 END) -
    SUM(CASE WHEN action = "out" AND DATE(date) <= CURDATE() THEN quantity ELSE 0 END)) = 0 
    THEN 0 
    ELSE 1 END ASC')
            ->orderBy('closing_balance', 'ASC')
            ->orderBy('total_value', 'ASC');

        $result = $itemBalances->get();
        return $result;
    }


    public function getInventoryStockByItem($itemId)
    {
        $itemBalanceOfItem = Item::withBalanceDetails($this->inventoryId, $itemId)->first();
        return $itemBalanceOfItem;
    }








    // $itemBalanceOfItem = DB::table('items')
    //     ->join('inventory_ledger_items', 'items.id', '=', 'inventory_ledger_items.item_id')
    //     ->joinSub(
    //         DB::table('supplier_items as si')
    //             ->join('item_prices as ip', 'si.id', '=', 'ip.supplier_item_id')
    //             ->select(
    //                 'si.item_id',
    //                 DB::raw('CAST(AVG(ip.price) AS DECIMAL(10,2)) AS average_price'),
    //                 'ip.base_uom_id'
    //             )
    //             ->whereIn(
    //                 'ip.id',
    //                 DB::table('item_prices as sub_ip')
    //                     ->select(DB::raw('MAX(sub_ip.id)'))
    //                     ->whereColumn('sub_ip.supplier_item_id', 'si.id')
    //                     ->groupBy('sub_ip.supplier_item_id')
    //             )
    //             ->groupBy('si.item_id', 'ip.base_uom_id'),
    //         'latest_prices',
    //         'items.id',
    //         'latest_prices.item_id'
    //     )
    //     ->join('uoms as item_uom', 'items.uom_id', '=', 'item_uom.id')
    //     ->join('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
    //     ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
    //     ->join('uom_conversions', function ($join) {
    //         $join->on('latest_prices.base_uom_id', '=', 'uom_conversions.base_unit_id')
    //             ->on('items.uom_id', '=', 'uom_conversions.conversion_unit_id')
    //             ->where('uom_conversions.is_active', 1);
    //     })
    //     ->select(
    //         'items.name',
    //         'inventory_ledger_items.item_id',
    //         'items.uom_id as item_uom_id',
    //         'latest_prices.base_uom_id as base_uom_id',
    //         'latest_prices.average_price as price',
    //         'items.base_uom_id as base_unit_id',
    //         'item_uom.name as conversion_uom_name',
    //         'base_uom.name as base_uom_name',
    //         'uom_conversions.conversion as conversion',
    //         DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) -
    //   SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as opening_balance'),
    //         DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as in_balance'),
    //         DB::raw('SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as out_balance'),
    //         DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) +
    //   SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) -
    //   SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) <= CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as closing_balance'),
    //         DB::raw('((latest_prices.average_price / uom_conversions.conversion) *
    //   (SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) +
    //    SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) -
    //    SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) <= CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END))) as total_value')
    //     )
    //     ->where('items.id', $itemId) // Filter for the specific item
    //     ->where('inventory_ledgers.inventory_id', $this->inventoryId)
    //     ->when($request->from_date && $request->to_date, function ($q) use ($from_date, $to_date) {
    //         $q->whereBetween(DB::raw('DATE(inventory_ledgers.created_at)'), [$from_date, $to_date]);
    //     })
    //     ->when($request->from_date && !$request->to_date, function ($q) use ($from_date) {
    //         $q->whereDate('inventory_ledgers.created_at', '>=', $from_date);
    //     })
    //     ->when(!$request->from_date && $request->to_date, function ($q) use ($to_date) {
    //         $q->whereDate('inventory_ledgers.created_at', '<=', $to_date);
    //     })
    //     ->groupBy(
    //         'inventory_ledger_items.item_id',
    //         'items.name',
    //         'latest_prices.average_price',
    //         'latest_prices.base_uom_id',
    //         'items.base_uom_id',
    //         'uom_conversions.conversion',
    //         'item_uom.name',
    //         'base_uom.name'
    //     )
    //     ->get();
    // return $itemBalanceOfItem;
}
