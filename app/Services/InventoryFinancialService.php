<?php

namespace App\Services;

use App\Models\Item;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class InventoryFinancialService
{
    // public function retrieveIn


    public function inventoryScheduleTotalWithTotalByMonth($categoryIds, $month)
    {
        // dd($categoryIds);
        // $categoryIds = [1, 4];
        // $totalConsumption = DB::table('order_items')
        //     ->join('menus', 'order_items.menu_id', '=', 'menus.id')
        //     ->join('menu_steps', 'menus.id', '=', 'menu_steps.menu_id')
        //     ->join('menu_step_items', 'menu_steps.menu_step_id', '=', 'menu_steps.id')
        //     ->join('items', 'item_menu.item_id', '=', 'items.id')
        //     ->where('order_items.status', 'done')
        //     ->whereIn('items.category_id', $categoryIds)
        //     ->whereMonth('order_items.date', $month) // Apply month filter
        //     ->select(DB::raw('COALESCE(SUM((item_menu.price * item_menu.weight) * order_items.quantity), 0) as total_consumption'))
        //     ->first();



        //     $totalConsumption = DB::table('order_items')
        //         ->join('menus', 'order_items.menu_id', '=', 'menus.id')
        //         ->join('menu_steps', function ($join) {
        //             $join->on('menus.id', '=', 'menu_steps.menu_id')
        //                 ->where('menu_steps.type', '=', 'ready_to_sale');
        //         })
        //         ->join('menu_step_items', 'menu_steps.id', '=', 'menu_step_items.menu_step_id')
        //         ->join('items', 'menu_step_items.item_id', '=', 'items.id')

        //         // 🔹 Latest active uom_conversion
        //         ->leftJoin(DB::raw('(
        //     SELECT uc.*
        //     FROM uom_conversions uc
        //     JOIN (
        //         SELECT item_id, MAX(id) as max_id
        //         FROM uom_conversions
        //         WHERE is_active = 1
        //         GROUP BY item_id
        //     ) latest_uc ON uc.id = latest_uc.max_id
        // ) as uom_conversions'), function ($join) {
        //             $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
        //                 ->whereColumn('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
        //                 ->whereColumn('uom_conversions.item_id', '=', 'items.id');
        //         })

        //         // 🔹 Base and item UOMs
        //         ->join('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
        //         ->join('uoms as item_uom', 'items.uom_id', '=', 'item_uom.id')

        //         // 🔹 Average price subquery
        //         ->leftJoin(DB::raw('(
        //     SELECT si.item_id, COALESCE(AVG(ip.price),0) as average_price
        //     FROM supplier_items si
        //     JOIN item_prices ip ON si.id = ip.supplier_item_id
        //     WHERE ip.id IN (
        //         SELECT MAX(sub_ip.id)
        //         FROM item_prices sub_ip
        //         WHERE sub_ip.supplier_item_id = si.id
        //     )
        //     GROUP BY si.item_id
        // ) as avg_prices'), 'avg_prices.item_id', '=', 'items.id')

        //         ->where('order_items.status', 'done')
        //         ->whereIn('items.category_id', $categoryIds)
        //         ->whereMonth('order_items.date', $month)

        //         ->select(
        //             'items.id as item_id',
        //             'items.name',
        //             'items.uom_id',
        //             'menu_step_items.uom_type',
        //             'base_uom.name as base_uom_name',
        //             'item_uom.name as item_uom',
        //             'uom_conversions.conversion as uom_conversion',
        //             DB::raw('COALESCE(avg_prices.average_price, 0) as average_price'),

        //             // 🔹 Define price based on uom_type
        //             DB::raw("
        //         CASE 
        //             WHEN menu_step_items.uom_type = 'uom' 
        //                 THEN COALESCE(avg_prices.average_price / NULLIF(uom_conversions.conversion,0), 0)
        //             WHEN menu_step_items.uom_type = 'base_uom' 
        //                 THEN COALESCE(avg_prices.average_price, 0)
        //             ELSE 0
        //         END as price
        //     "),

        //             // 🔹 Total quantity
        //             DB::raw('COALESCE(SUM(menu_step_items.quantity * order_items.quantity), 0) as total_quantity'),

        //             // 🔹 Consumption cost using calculated price
        //             DB::raw("
        //         COALESCE(SUM(
        //             menu_step_items.quantity * order_items.quantity * 
        //             (CASE 
        //                 WHEN menu_step_items.uom_type = 'uom' 
        //                     THEN COALESCE(avg_prices.average_price / NULLIF(uom_conversions.conversion,0), 0)
        //                 WHEN menu_step_items.uom_type = 'base_uom' 
        //                     THEN COALESCE(avg_prices.average_price, 0)
        //                 ELSE 0
        //             END)
        //         ), 0) as total_consumption_cost
        //     ")
        //         )
        //         ->groupBy(
        //             'items.id',
        //             'items.name',
        //             'items.uom_id',
        //             'menu_step_items.uom_type',
        //             'base_uom.name',
        //             'item_uom.name',
        //             'uom_conversions.conversion',
        //             'avg_prices.average_price'
        //         )
        //         ->get();
        $totalConsumption = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('menu_steps', function ($join) {
                $join->on('menus.id', '=', 'menu_steps.menu_id')
                    ->where('menu_steps.type', '=', 'ready_to_sale');
            })
            ->join('menu_step_items', 'menu_steps.id', '=', 'menu_step_items.menu_step_id')
            ->join('items', 'menu_step_items.item_id', '=', 'items.id')
            ->leftJoin(DB::raw('(
        SELECT uc.*
        FROM uom_conversions uc
        JOIN (
            SELECT item_id, MAX(id) as max_id
            FROM uom_conversions
            WHERE is_active = 1
            GROUP BY item_id
        ) latest_uc ON uc.id = latest_uc.max_id
    ) as uom_conversions'), function ($join) {
                $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
                    ->whereColumn('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
                    ->whereColumn('uom_conversions.item_id', '=', 'items.id');
            })
            ->leftJoin(DB::raw('(
        SELECT si.item_id, COALESCE(AVG(ip.price),0) as average_price
        FROM supplier_items si
        JOIN item_prices ip ON si.id = ip.supplier_item_id
        WHERE ip.id IN (
            SELECT MAX(sub_ip.id)
            FROM item_prices sub_ip
            WHERE sub_ip.supplier_item_id = si.id
        )
        GROUP BY si.item_id
    ) as avg_prices'), 'avg_prices.item_id', '=', 'items.id')
            ->where('order_items.status', 'done')
            ->whereIn('items.category_id', $categoryIds)
            ->whereMonth('order_items.date', $month)
            ->select(DB::raw("
        COALESCE(SUM(
            menu_step_items.quantity * order_items.quantity *
            (CASE 
                WHEN menu_step_items.uom_type = 'uom'
                    THEN COALESCE(avg_prices.average_price / NULLIF(uom_conversions.conversion,0), 0)
                WHEN menu_step_items.uom_type = 'base_uom'
                    THEN COALESCE(avg_prices.average_price, 0)
                ELSE 0
            END)
        ), 0) as grand_total
    "))
            ->value('total_consumption');


        return $totalConsumption;
    }
    public function inventoryScheduleWithTypeByMonth($currentMonth)
    {
        $itemSummary = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
            // Subquery for Purchase Order Data
            ->leftJoin(DB::raw('(SELECT 
        items.category_id,
        items.item_type_id,
        SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount,
        SUM(po_grns.invoice_amount) as cash_purchase_amount,
        SUM(purchase_order_items.quantity * purchase_order_items.amount) - SUM(po_grns.invoice_amount) as credit_purchase_amount
    FROM purchase_order_items
    JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
    JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
    JOIN items ON purchase_order_items.item_id = items.id
    WHERE purchase_orders.is_bought = 1
    AND MONTH(purchase_orders.purchased_date_time) = ' . $currentMonth . '
    GROUP BY items.category_id, items.item_type_id) AS purchase_orders_summary'), function ($join) {
                $join->on('items.category_id', '=', 'purchase_orders_summary.category_id')
                    ->on('items.item_type_id', '=', 'purchase_orders_summary.item_type_id');
            })
            // Subquery for Item Consumption Data
            ->leftJoin(DB::raw('(SELECT 
        items.category_id,
        items.item_type_id,
        SUM((item_menu.price * item_menu.weight) * order_items.quantity) as consumption
    FROM order_items
    JOIN item_menu ON order_items.menu_id = item_menu.menu_id
    JOIN items ON item_menu.item_id = items.id
    WHERE order_items.status = "sold"
    GROUP BY items.category_id, items.item_type_id) AS item_consumption_summary'), function ($join) {
                $join->on('items.category_id', '=', 'item_consumption_summary.category_id')
                    ->on('items.item_type_id', '=', 'item_consumption_summary.item_type_id');
            })
            // Subquery for Previous Months' Closing Balance
            ->leftJoin(DB::raw('(SELECT 
        items.category_id,
        items.item_type_id,
        SUM(
            COALESCE(purchase_order_items.quantity * purchase_order_items.amount, 0) 
            - COALESCE((
                SELECT SUM((item_menu.price * item_menu.weight) * order_items.quantity) 
                FROM order_items 
                JOIN item_menu ON order_items.menu_id = item_menu.menu_id 
                WHERE order_items.status = "sold"
                AND MONTH(order_items.created_at) < ' . $currentMonth . '
            ), 0)
        ) as closing_balance
    FROM items
    LEFT JOIN purchase_order_items ON items.id = purchase_order_items.item_id
    LEFT JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
    LEFT JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
    WHERE purchase_orders.is_bought = 1
    AND MONTH(purchase_orders.purchased_date_time) < ' . $currentMonth . '
    GROUP BY items.category_id, items.item_type_id) AS previous_month_summary'), function ($join) {
                $join->on('items.category_id', '=', 'previous_month_summary.category_id')
                    ->on('items.item_type_id', '=', 'previous_month_summary.item_type_id');
            })
            // Select Required Fields
            ->select(
                'categories.name as category_name',
                'categories.id as category_id',
                'item_types.name as item_type_name',
                'item_types.id as item_type_id',
                DB::raw('COALESCE(purchase_orders_summary.total_amount, 0) as total_amount'),
                DB::raw('COALESCE(purchase_orders_summary.cash_purchase_amount, 0) as cash_purchase_amount'),
                DB::raw('COALESCE(purchase_orders_summary.credit_purchase_amount, 0) as credit_purchase_amount'),
                DB::raw('COALESCE(item_consumption_summary.consumption, 0) as consumption'),
                DB::raw('(COALESCE(purchase_orders_summary.total_amount, 0) - COALESCE(item_consumption_summary.consumption, 0)) as closing_balance'),
                DB::raw('COALESCE(previous_month_summary.closing_balance, 0) as opening_balance')
            )
            ->groupBy('categories.name', 'item_types.name', 'items.category_id', 'items.item_type_id')
            ->get();
        return $itemSummary;
    }
    public function inventoryScheduleWithCategoryByMonth($year, $currentMonth)
    {
        // $itemSummary = DB::table('items')
        //     ->join('categories', 'items.category_id', '=', 'categories.id')
        //     // Subquery for Purchase Order Data
        //     ->leftJoin(DB::raw('(SELECT 
        //     items.category_id,
        //     SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount,
        //     SUM(po_grns.invoice_amount) as cash_purchase_amount,
        //     SUM(purchase_order_items.quantity * purchase_order_items.amount) - SUM(po_grns.invoice_amount) as credit_purchase_amount
        // FROM purchase_order_items
        // JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
        // JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
        // JOIN items ON purchase_order_items.item_id = items.id
        // WHERE purchase_orders.is_bought = 1
        // AND MONTH(purchase_orders.purchased_date_time) = ' . $currentMonth . '
        // GROUP BY items.category_id) AS purchase_orders_summary'), function ($join) {
        //         $join->on('items.category_id', '=', 'purchase_orders_summary.category_id');
        //     })
        //     // Subquery for Item Consumption Data
        //     ->leftJoin(DB::raw('(SELECT 
        //     items.category_id,
        //     SUM((item_menu.price * item_menu.weight) * order_items.quantity) as consumption
        // FROM order_items
        // JOIN item_menu ON order_items.menu_id = item_menu.menu_id
        // JOIN items ON item_menu.item_id = items.id
        // WHERE order_items.status = "sold"
        // GROUP BY items.category_id) AS item_consumption_summary'), function ($join) {
        //         $join->on('items.category_id', '=', 'item_consumption_summary.category_id');
        //     })
        //     // Subquery for Previous Months' Closing Balance
        //     ->leftJoin(DB::raw('(SELECT 
        //     items.category_id,
        //     SUM(
        //         COALESCE(purchase_order_items.quantity * purchase_order_items.amount, 0) 
        //         - COALESCE((
        //             SELECT SUM((item_menu.price * item_menu.weight) * order_items.quantity) 
        //             FROM order_items 
        //             JOIN item_menu ON order_items.menu_id = item_menu.menu_id 
        //             WHERE order_items.status = "sold"
        //             AND MONTH(order_items.created_at) < ' . $currentMonth . '
        //         ), 0)
        //     ) as closing_balance
        // FROM items
        // LEFT JOIN purchase_order_items ON items.id = purchase_order_items.item_id
        // LEFT JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
        // LEFT JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
        // WHERE purchase_orders.is_bought = 1
        // AND MONTH(purchase_orders.purchased_date_time) < ' . $currentMonth . '
        // GROUP BY items.category_id) AS previous_month_summary'), function ($join) {
        //         $join->on('items.category_id', '=', 'previous_month_summary.category_id');
        //     })
        //     // Select Required Fields
        //     ->select(
        //         'categories.name as category_name',
        //         DB::raw('COALESCE(purchase_orders_summary.total_amount, 0) as total_amount'),
        //         DB::raw('COALESCE(purchase_orders_summary.cash_purchase_amount, 0) as cash_purchase_amount'),
        //         DB::raw('COALESCE(purchase_orders_summary.credit_purchase_amount, 0) as credit_purchase_amount'),
        //         DB::raw('COALESCE(item_consumption_summary.consumption, 0) as consumption'),
        //         DB::raw('(COALESCE(purchase_orders_summary.total_amount, 0) - COALESCE(item_consumption_summary.consumption, 0)) as closing_balance'),
        //         DB::raw('COALESCE(previous_month_summary.closing_balance, 0) as opening_balance')
        //     )
        //     ->groupBy('categories.id') // Only group by categories.id
        //     ->get();

        $itemSummary = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            // Subquery for Purchase Order Data
            ->leftJoin(DB::raw('(SELECT 
            items.category_id,
            SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount,
            SUM(po_grns.invoice_amount) as cash_purchase_amount,
            SUM(purchase_order_items.quantity * purchase_order_items.amount) - SUM(po_grns.invoice_amount) as credit_purchase_amount
        FROM purchase_order_items
        JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
        JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
        JOIN items ON purchase_order_items.item_id = items.id
        WHERE purchase_orders.is_bought = 1
        AND YEAR(purchase_orders.purchased_date_time) = ' . $year . '
        AND MONTH(purchase_orders.purchased_date_time) = ' . $currentMonth . '
        GROUP BY items.category_id) AS purchase_orders_summary'), function ($join) {
                $join->on('items.category_id', '=', 'purchase_orders_summary.category_id');
            })
            // Subquery for Item Consumption Data
            ->leftJoin(DB::raw('(SELECT 
            items.category_id,
            SUM((item_menu.price * item_menu.weight) * order_items.quantity) as consumption
        FROM order_items
        JOIN item_menu ON order_items.menu_id = item_menu.menu_id
        JOIN items ON item_menu.item_id = items.id
        WHERE order_items.status = "sold"
        AND YEAR(order_items.created_at) = ' . $year . '
        AND MONTH(order_items.created_at) = ' . $currentMonth . '
        GROUP BY items.category_id) AS item_consumption_summary'), function ($join) {
                $join->on('items.category_id', '=', 'item_consumption_summary.category_id');
            })
            // Subquery for Previous Months' Closing Balance
            ->leftJoin(DB::raw('(SELECT 
            items.category_id,
            SUM(
                COALESCE(purchase_order_items.quantity * purchase_order_items.amount, 0) 
                - COALESCE((
                    SELECT SUM((item_menu.price * item_menu.weight) * order_items.quantity) 
                    FROM order_items 
                    JOIN item_menu ON order_items.menu_id = item_menu.menu_id 
                    WHERE order_items.status = "sold"
                    AND YEAR(order_items.created_at) = ' . $year . '
                    AND MONTH(order_items.created_at) < ' . $currentMonth . '
                ), 0)
            ) as closing_balance
        FROM items
        LEFT JOIN purchase_order_items ON items.id = purchase_order_items.item_id
        LEFT JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
        LEFT JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
        WHERE purchase_orders.is_bought = 1
        AND YEAR(purchase_orders.purchased_date_time) = ' . $year . '
        AND MONTH(purchase_orders.purchased_date_time) < ' . $currentMonth . '
        GROUP BY items.category_id) AS previous_month_summary'), function ($join) {
                $join->on('items.category_id', '=', 'previous_month_summary.category_id');
            })
            // Select Required Fields
            ->select(
                'categories.name as category_name',
                DB::raw('COALESCE(purchase_orders_summary.total_amount, 0) as total_amount'),
                DB::raw('COALESCE(purchase_orders_summary.cash_purchase_amount, 0) as cash_purchase_amount'),
                DB::raw('COALESCE(purchase_orders_summary.credit_purchase_amount, 0) as credit_purchase_amount'),
                DB::raw('COALESCE(item_consumption_summary.consumption, 0) as consumption'),
                DB::raw('(COALESCE(purchase_orders_summary.total_amount, 0) - COALESCE(item_consumption_summary.consumption, 0)) as closing_balance'),
                DB::raw('COALESCE(previous_month_summary.closing_balance, 0) as opening_balance')
            )
            ->groupBy('categories.id') // Only group by categories.id
            ->get();

        return $itemSummary;
    }


}