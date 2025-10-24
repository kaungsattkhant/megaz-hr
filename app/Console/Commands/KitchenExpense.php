<?php

namespace App\Console\Commands;

use App\Models\PurchaseOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KitchenExpense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kitchen-expense';

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

            $kitchenTotalExpense = PurchaseOrder::where('type', 'restaurant')
            // ->where('status', 'md_checked')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_price');

            Log::info("Kitchen total expense for {$monthName} ({$year}) is {$kitchenTotalExpense}");
            if($kitchenTotalExpense == 0){
                $this->info("No kitchen total expense data found for {$monthName}");
                return Command::SUCCESS;
            }

            DB::table('kitchen_expenses')->updateOrInsert(
                [
                    'year' => $year,
                    'month_name' => $monthName,
                    'month_number' => $month,
                ],
                [
                    'kitchen_total_expense' => $kitchenTotalExpense,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            
        } catch (\Exception $e) {
            Log::error("Failed to calculate kitchen total expense: " . $e->getMessage());
        }
    }
}
