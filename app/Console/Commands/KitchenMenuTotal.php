<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KitchenMenuTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kitchen-menu-total';

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
        DB::beginTransaction();
        try {
            $year = now()->year;
            $month = now()->month;
            $monthName = now()->format('M');
            // Calculate total for current month
            $records = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->join('invoices', 'orders.invoice_id', '=', 'invoices.id')
                ->join('areas as selling_area', 'invoices.area_id', '=', 'selling_area.id')
                ->join('areas as cooking_area', 'order_items.area_id', '=', 'cooking_area.id')
                ->join('area_types', 'selling_area.area_type_id', '=', 'area_types.id')
                ->where('cooking_area.type', 'restaurant')
                ->whereYear('order_items.date', $year)
                ->whereMonth('order_items.date', $month)
                ->selectRaw('
                menus.name as menu_name,
                area_types.type as area_type,
                cooking_area.type as cooking_area_type,
                SUM(order_items.quantity) as total_menus_quantity
            ')
                ->groupBy('area_types.type', 'cooking_area.type', 'menus.name')
                ->get();

            if ($records->isEmpty()) {
                $this->info("No kitchen data found for {$monthName}");
                return Command::SUCCESS;
            }

            foreach ($records as $record) {
                DB::table('monthly_kitchen_menus')->updateOrInsert(
                    [
                        'year' => $year,
                        'month_number' => $month,
                        'area_type' => $record->area_type,
                        'cooking_area_type' => $record->cooking_area_type,
                        'menu_name' => $record->menu_name,
                    ],
                    [
                        'month_name' => $monthName,
                        'total_menu_sale' => $record->total_menus_quantity,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $successMessage = "Monthly kitchen menu total added successfully for {$monthName} {$year}.";
            $this->info($successMessage);
            Log::info($successMessage);
            DB::commit();
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = "Failed to add monthly kitchen menu total: " . $e->getMessage();
            $this->error($errorMessage);
            Log::error($errorMessage);
            Log::error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
