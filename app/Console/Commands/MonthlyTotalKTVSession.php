<?php

namespace App\Console\Commands;

use App\Models\EntitySession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyTotalKTVSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-total-k-t-v-session';

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
            // Calculate total for current month
            $monthlyTotalKTVSessions = EntitySession::whereHas('entity',function ($query){
                $query->where('entity_type','room');
            })
            ->whereHas('entity.area.areaType',function ($query){
                $query->where('type','ktv');
            })
            ->whereHas('entity.invoices',function ($q) use ($year, $month){
                $q->whereYear('invoice_date', $year)
                ->whereMonth('invoice_date', $month);
            })->count();

            Log::info([
                "Monthly ktv session" => $monthlyTotalKTVSessions,
                "Month" => $monthName,
                "Year" => $year,
            ]);

            
            if ($monthlyTotalKTVSessions == 0) {
                $this->info("No total_ktv_session data found for {$monthName}");
                return Command::SUCCESS;
            }

            //  Update or insert this month’s total
                DB::table('monthly_total_ktv_sessions')->updateOrInsert(
                    [
                        'year' => $year,
                        'month_name' => $monthName,
                        'month_number' => $month,
                    ],
                    [
                        'total_ktv_sessions' => $monthlyTotalKTVSessions,
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
