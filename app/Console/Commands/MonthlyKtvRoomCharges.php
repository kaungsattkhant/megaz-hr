<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyKtvRoomCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-ktv-room-charges';

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
            $monthlyTotalKtvRoomCharges = Invoice::whereHas('entity', function ($query) {
                $query->where('entity_type', 'room');
            })
                ->whereHas('entity.area.areaType', function ($query) {
                    $query->where('type', 'ktv');
                })
                ->whereHas('entity.invoices', function ($query) use ($year, $month) {
                    $query->whereYear('invoice_date', $year)
                        ->whereMonth('invoice_date', $month);
                })->sum('total_session_price');
            Log::info([
                "Monthly KTV room charges" => $monthlyTotalKtvRoomCharges,
                "Month" => $monthName,
                "Year" => $year,
            ]);

            if ($monthlyTotalKtvRoomCharges == 0) {
                $this->info("No total ktv room charges data found for {$monthName}");
                return Command::SUCCESS;
            }

            DB::table('monthly_total_ktv_room_charges')->updateOrInsert(
                [
                    'year' => $year,
                    'month_name' => $monthName,
                    'month_number' => $month,
                ],
                [
                    'total_ktv_room_charges' => $monthlyTotalKtvRoomCharges,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            $this->info("Monthly KTV room charges data saved successfully");
        } catch (\Exception $e) {
            $this->error('Failed to refresh total KTV room charges: ' . $e->getMessage());
            Log::error('Failed to refresh total KTV room charges: ' . $e->getMessage());
        }
        return Command::SUCCESS;
    }
}
