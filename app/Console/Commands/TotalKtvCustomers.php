<?php

namespace App\Console\Commands;

use App\Models\HeadCount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TotalKtvCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:total-ktv-customers';

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
            $totalKTVCustomers = HeadCount::whereHas('invoice.entity.area.areaType',function ($query){
                $query->where('type','KTV');
            })
            ->whereHas('invoice.entity.area',function ($query){
                $query->where('entity_type','room');
            })
            ->whereHas('invoice',function ($q) use ($year, $month){
                $q->whereYear('invoice_date', $year)
                ->whereMonth('invoice_date', $month);
            })->sum('total_head_count');
            
            if ($totalKTVCustomers == 0) {
                $this->info("No total_ktv_customers data found for {$monthName}");
                return Command::SUCCESS;
            }

            //  Update or insert this month’s total
                DB::table('total_ktv_customers')->updateOrInsert(
                    [
                        'year' => $year,
                        'month_number' => $month,
                    ],
                    [
                        'month_name' => $monthName,
                        'total_ktv_customer' => $totalKTVCustomers,
                        'area_type' => 'KTV',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            
            $this->info(" total_ktv_customers refreshed successfully for {$monthName} ({$year})");
        } catch (\Exception $e) {
            $this->error(' Failed to refresh total_ktv_customers: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
