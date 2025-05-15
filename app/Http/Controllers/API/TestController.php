<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\Inventory;

use Illuminate\Http\Request;

use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Inventory\GetInventoryStockAction;
use Reinbier\LaravelHoliday\Facades\LaravelHoliday;

class TestController extends Controller
{
    //
    public function index()
    {
        $invoiceId = 1;
        $categoryIds = [1, 2, 3, 4];
        $menus = Invoice::where('invoices.id', $invoiceId)
            ->whereIn('menu_categories.id', $categoryIds)
            ->join('orders', 'invoices.id', '=', 'orders.invoice_id') // Join orders
            ->join('order_items', 'orders.id', '=', 'order_items.order_id') // Join order items
            ->join('menus', 'order_items.menu_id', '=', 'menus.id') // Join menus
            ->join('menu_categories', 'menus.menu_category_id', '=', 'menu_categories.id') // Join menu categories
            ->join('areas', 'order_items.area_id', '=', 'areas.id') // Join areas
            ->join('area_categories', 'areas.area_category_id', '=', 'area_categories.id') // Join area categories
            ->join('area_types', 'areas.area_type_id', '=', 'area_types.id') // Join area types
            ->select(
                'menu_categories.id as category_id',
                'menu_categories.name as category_name',
                'areas.id as area_id',
                'areas.name AS area_name',
                'areas.area_category_id as area_category_id',
                'area_categories.name AS area_category_name',
                'areas.area_type_id as area_type_id',
                'area_types.name AS area_type_name',
                DB::raw('COUNT(order_items.id) as menu_count'), // Count number of menu items in each category
                DB::raw('SUM(order_items.price) as total_price') // Sum of order item prices per category
            )
            ->groupBy(
                'menu_categories.id',
                'menu_categories.name',
                'areas.id',
                'areas.name',
                'areas.area_category_id',
                'area_categories.name',
                'areas.area_type_id',
                'area_types.name',
            ) // Group by category
            ->orderBy('menu_categories.name')
            ->get();

        // return $menus;
        ResponseData($menus);
        // $items = (new GetInventoryStockAction(2))->run();

        // ResponseData($items);
    }

    public function getHolidays()
    {
        $holiday = LaravelHoliday::model();
        return $holiday;
        LaravelHoliday::forYear(2025)
            ->addHoliday('2025-07-02', 'National-Day')
            ->getHolidays();
        $holidays = LaravelHoliday::getHolidays();
        ResponseMessage($holidays, 200);
    }
}
