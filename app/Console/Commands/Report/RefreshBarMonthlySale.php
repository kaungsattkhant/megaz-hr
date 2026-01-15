<?php

namespace App\Console\Commands\Report;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefreshBarMonthlySale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-bar-monthly-sale';

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
        try {
            $year = now()->year;
            $month = now()->month;
            $monthName = now()->format('M');
            // Calculate total for current month
            $records = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('invoices', 'orders.invoice_id', '=', 'invoices.id')
                ->join('areas as selling_area', 'invoices.area_id', '=', 'selling_area.id')
                ->join('areas as cooking_area', 'order_items.area_id', '=', 'cooking_area.id')
                ->join('area_types', 'selling_area.area_type_id', '=', 'area_types.id')
                
                ->whereYear('order_items.date', $year)
                ->whereMonth('order_items.date', $month)
                ->selectRaw('
                    area_types.type as area_type,
                    cooking_area.type as cooking_area_type,
                    SUM(order_items.sub_total_price) as total
                ')
                ->groupBy('area_types.type', 'cooking_area.type')
                ->get();

                Log::info([
                    "Bar monthly sale" => $records,
                    "Month" => $monthName,
                    "Year" => $year,
                ]);
            if ($records->isEmpty()) {
                $this->info("ℹ No bar data found for {$monthName}");
                return Command::SUCCESS;
            }

            //  Update or insert this month’s total
            foreach ($records as $record) {
                DB::table('bar_monthly_sale')->updateOrInsert(
                    [
                        'year' => $year,
                        'month_number' => $month,
                        'area_type' => $record->area_type,
                        'cooking_area_type' => $record->cooking_area_type,
                    ],
                    [
                        'month_name' => $monthName,
                        'total' => $record->total,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $this->info(" bar_monthly_sale refreshed successfully for {$monthName} ({$year})");
        } catch (\Exception $e) {
            $this->error(' Failed to refresh bar_for_sky: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
