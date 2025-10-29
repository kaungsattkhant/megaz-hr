<?php

namespace App\Console\Commands;

use Exception;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Area;
use App\Models\TargetMenu;

class RefreshTargetActualMonthlyMenuSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-target-actual-monthly-menu-sale {areaToQuery?}';

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
        $areaArg = $this->argument('areaToQuery');
        $areaKeyword = ($areaArg)? "%{$areaArg}%": "%Sky%";
        $targetArea = Area::where('name','like',$areaKeyword)->first();
        if(!$targetArea){
            $this->error("No area with the given keyword found for target actual sales report");
            return;
        }

        $areaId    = $targetArea->id;
        $areaName  = $targetArea->name;

        $year = now()->year;
        $month = now()->month;
        $monthName = now()->format('M');

        $records = TargetMenu::query()
            ->from('target_menus as tm')
            ->leftJoin('order_items as oi', function ($join) {
                $join->on('oi.area_id', '=', 'tm.area_id')
                    ->on('oi.menu_id', '=', 'tm.menu_id');
            })
            ->join('sale_target_menus as stm', 'tm.sale_target_menu_id', '=', 'stm.id')
            ->join('areas as a', 'tm.area_id', '=', 'a.id')
            ->whereYear('stm.month', $year)
            ->whereMonth('stm.month', $month)

            ->where(function ($query) use ($areaId, $areaName) {
                $query->where('a.id', $areaId)
                    ->orWhere('a.name', 'like', $areaName);
            })
            ->selectRaw('
                stm.id AS sale_target_menu_id,
                tm.area_id,
                tm.menu_id,
                tm.quantity AS target_quantity,
                COALESCE(SUM(oi.original_price * tm.quantity), 0) AS target_sales_amount,
                COALESCE(SUM(oi.quantity), 0) AS actual_quantity,
                COALESCE(SUM(oi.sub_total_price) * SUM(oi.quantity), 0) AS actual_sales_amount,
                ROUND(
                    (COALESCE(SUM(oi.quantity), 0) / NULLIF(tm.quantity, 0)) * 100,
                    2
                ) AS achieved_percentage
            ')
            ->groupBy('stm.id', 'tm.area_id', 'tm.menu_id', 'tm.quantity', 'a.name')
            ->orderBy('tm.area_id')
            ->orderBy('tm.menu_id')
            ->get();

        try{
            DB::beginTransaction();
            foreach($records as $record){
                DB::table('target_actual_monthly_menu_sales')->updateOrInsert(
                    [
                        'year' => $year,
                        'month_number' => $month,
                        'area_id' => $record->area_id,
                        'menu_id' => $record->menu_id,
                    ],
                    [
                        'year' => $year,
                        'month_number' => $month,
                        'month_name' => $monthName,
                        'area_id' => $record->area_id,
                        'menu_id' => $record->menu_id,
                        'target_quantity' => $record->target_quantity,
                        'target_sales_amount' => $record->target_sales_amount,
                        'actual_quantity' => $record->actual_quantity,
                        'actual_sales_amount' => $record->actual_sales_amount,
                        'achieved_percentage' => $record->achieved_percentage,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            DB::commit();
            $this->info("target_actual_monthly_menu_sales for {$targetArea->name} refreshed successfully for {$monthName} ({$year})");
        }catch(Exception $e){
            DB::rollBack();
            $this->error("Failed to refresh target_actual_monthly_menu_sales for {$targetArea->name}: " . $e->getMessage());
        }
    }
}
