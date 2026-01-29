<?php

namespace App\Console\Commands\Report;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleByCategoryOfArea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sale-by-category-of-area';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        try{
            // Carbon::setTestNow(Carbon::parse('2026-01-29 00:00:00'));
            
            $currentDate = Carbon::parse(now())->subDays(1);
            $year = $currentDate->year;
            $currentMonth = $currentDate->month;
          
            $records = DB::table('order_items')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('invoices', 'orders.invoice_id', '=', 'invoices.id')
                ->join('areas as selling_area', 'invoices.area_id', '=', 'selling_area.id')
                ->join('area_types', 'selling_area.area_type_id', '=', 'area_types.id')
                // ->whereYear('order_items.date', $year)
                // ->whereMonth('order_items.date', $month)
                ->whereDate('order_items.date', $currentDate)
                ->selectRaw('
            selling_area.id as selling_area_id,
                    area_types.type as area_type,
                    SUM(order_items.sub_total_price) as total,
                    SUM(order_items.quantity) as total_qty,
                    SUM(CASE WHEN order_items.is_foc = 0 THEN 1 ELSE 0 END) as total_sale_qty,
                    SUM(CASE WHEN order_items.is_foc = 1 THEN 1 ELSE 0 END) as total_foc,
                    menus.id as menu_id
                ')
                ->groupBy('menus.id', 'invoices.area_id')
                ->get();
            Log::info([
                "Sael By Area" => $records,
                "Date " => $currentDate,
            ]);
            foreach ($records as $record) {
                DB::table('sale_by_area')->updateOrInsert(
                    [
                        'date_time' => $currentDate,
                        'menu_id' => $record->menu_id,
                        'selling_area_id' => $record->selling_area_id,
                    ],
                    [
                        'total_qty' => $record->total_qty,
                        'total_sale_qty' => $record->total_sale_qty,
                        'total_foc' => $record->total_foc,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::info(["Sale By Category Of Area Error" => $e->getMessage()]);
        }
    }
}
