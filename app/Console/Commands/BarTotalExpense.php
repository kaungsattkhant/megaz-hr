<?php

namespace App\Console\Commands;

use App\Models\PurchaseOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BarTotalExpense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bar-total-expense';

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

            $barTotalExpense = PurchaseOrder::where('type', 'bar')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_price');

            Log::info("Bar total expense for {$monthName} ({$year}) is {$barTotalExpense}");
            if($barTotalExpense == 0){
                $this->info("No bar total expense data found for {$monthName}");
                return Command::SUCCESS;
            }

            DB::table('bar_department_expenses')->updateOrInsert(
                [
                    'year' => $year,
                    'month_name' => $monthName,
                    'month_number' => $month,
                ],
                [
                    'bar_total_expense' => $barTotalExpense,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            
        } catch (\Exception $e) {
            Log::error("Failed to calculate bar total expense: " . $e->getMessage());
        }
    }
}
