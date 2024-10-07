<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InventoryFinancialService
{
    // public function retrieveIn


    public function inventoryScheduleTotalWithTotalByMonth($categoryIds, $month)
    {
        // dd($categoryIds);
        // $categoryIds = [1, 4];
        $totalConsumption = DB::table('order_items')
        ->join('item_menu', 'order_items.menu_id', '=', 'item_menu.menu_id')
        ->join('items', 'item_menu.item_id', '=', 'items.id')
        ->where('order_items.status', 'sold')
        ->whereIn('items.category_id', $categoryIds)
        ->whereMonth('order_items.date', $month) // Apply month filter
        ->select(DB::raw('COALESCE(SUM((item_menu.price * item_menu.weight) * order_items.quantity), 0) as total_consumption'))
        ->first();
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
    public function inventoryScheduleWithCategoryByMonth($currentMonth)
    {
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
                    AND MONTH(order_items.created_at) < ' . $currentMonth . '
                ), 0)
            ) as closing_balance
        FROM items
        LEFT JOIN purchase_order_items ON items.id = purchase_order_items.item_id
        LEFT JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
        LEFT JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
        WHERE purchase_orders.is_bought = 1
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