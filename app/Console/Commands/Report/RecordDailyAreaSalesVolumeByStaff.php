<?php

namespace App\Console\Commands\Report;

use Exception;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecordDailyAreaSalesVolumeByStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:record-daily-area-sales-volume-by-staff {startDateArg?} {endDateArg?}';

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
        $startDateArg = $this->argument('startDateArg');
        $startDate = ($startDateArg)? $startDateArg: CurrentDate();
        if(!IsValidDateString($startDate)){
            $this->error("Start date is not valid");
            return;
        }

        $endDateArg = $this->argument('endDateArg');
        $endDate = ($endDateArg)? $endDateArg: $startDate;
        if(!IsValidDateString($endDate)){
            $this->error("End date is not valid");
            return;
        }

        $targetDate = date_create($startDate);
        $year = (int) $targetDate->format('Y');
        $month = (int) $targetDate->format('m');
        $monthName = $targetDate->format('M');

        $records = DB::table('staff_timeshifts as sts')
            ->join('staff as stf', 'sts.staff_id', '=', 'stf.id')
            ->join('areas as a', 'sts.area_id', '=', 'a.id')
            ->join('area_categories as ac', 'a.area_category_id', '=', 'ac.id')
            ->leftJoin('invoices as inv', function ($join) {
                $join->on('inv.area_id', '=', 'sts.area_id')
                    ->whereRaw('DATE(inv.invoice_date) = DATE(sts.date_time)');
            })
            ->leftJoin('head_counts as hc', 'inv.head_count_id', '=', 'hc.id')
            ->where('ac.name', 'Selling Area')
            ->whereNotNull('sts.area_id')
            ->whereBetween(DB::raw('DATE(sts.date_time)'), [$startDate, $endDate])
            ->where('inv.payment_status','paid')
            ->selectRaw('
                stf.id AS staff_id,
                stf.name AS staff_name,
                DATE(sts.date_time) AS work_date,
                a.id AS area_id,
                a.name AS area_name,
                COALESCE(SUM(inv.sub_total), 0) AS total_sales,
                COALESCE(SUM(hc.total_head_count), 0) AS total_head_count,
                ROUND(
                CASE
                    WHEN COALESCE(SUM(hc.total_head_count), 0) = 0 THEN 0
                    ELSE COALESCE(SUM(inv.total), 0) / COALESCE(SUM(hc.total_head_count), 0)
                END,
                2
                ) AS sale_amount_per_head
            ')
            ->groupBy('stf.id', 'stf.name', DB::raw('DATE(sts.date_time)'), 'a.id', 'a.name', 'ac.name')
            ->orderBy(DB::raw('DATE(sts.date_time)'))
            ->orderBy('stf.name')
            ->get();

        try{
            DB::beginTransaction();
            foreach($records as $record){
                DB::table('daily_area_sales_volume_by_staff')->updateOrInsert(
                    [
                        'work_date' => $record->work_date,
                        'staff_id' => $record->staff_id,
                        'area_id' => $record->area_id,
                    ],
                    [
                        'year' => $year,
                        'month_number' => $month,
                        'month_name' => $monthName,
                        'work_date' => $record->work_date,
                        'staff_id' => $record->staff_id,
                        'area_id' => $record->area_id,

                        'total_amount' => $record->total_sales,
                        'total_pax' => $record->total_head_count,
                        'per_pax' => $record->sale_amount_per_head,

                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            DB::commit();
            $this->info("daily_area_sales_volume_by_staff recorded successfully");
        }catch(Exception $e){
            DB::rollBack();
            $this->error("Failed to record daily_area_sales_volume_by_staff" . $e->getMessage());
        }

    }
}
