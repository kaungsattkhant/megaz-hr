<?php

namespace App\Console\Commands\Report;

use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyTotalKTVSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-total-k-t-v-sales';

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
        try {
            $year = now()->year;
            $month = now()->month;
            $monthName = now()->format('M');

            $monthlyTotalKTVSales = Invoice::whereYear('invoice_date', $year)
                ->whereMonth('invoice_date', $month)
                ->whereHas('entity.area.areaType',function ($query){
                    $query->where('type','ktv');
                })
                ->sum('sub_total');

            Log::info([
                "Monthly ktv sales" =>  $monthlyTotalKTVSales,
                "Month" => $monthName,
                "Year" => $year,
            ]);

            
            if ($monthlyTotalKTVSales == 0) {
                $this->info("No total ktv sales data found for {$monthName}");
                return Command::SUCCESS;
            }

            //  Update or insert this month’s total
                DB::table('monthly_total_ktv_sales')->updateOrInsert(
                    [
                        'year' => $year,
                        'month_name' => $monthName,
                        'month_number' => $month,
                    ],
                    [
                        'total_ktv_sales' => $monthlyTotalKTVSales,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            
            $this->info("Total_ktv_sessions refreshed successfully for {$monthName} ({$year})");
        } catch (\Exception $e) {
            $this->error(' Failed to refresh total_ktv_sessions: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
