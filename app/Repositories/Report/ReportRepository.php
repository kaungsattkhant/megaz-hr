<?php
namespace App\Repositories\Report;

use App\Models\Area;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportInterface
{
    public function getBarForSky($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('bar_monthly_sale')
            ->select('year','month_name', 'area_type', 'cooking_area_type', DB::raw('SUM(total) as amount'))
            ->groupBy('year','month_name', 'area_type', 'cooking_area_type')
            ->get()
            ->groupBy('month_name')
            ->map(function($items) {
                $firstItem = $items->first();
                return [
                    'year' => $firstItem->year,
                    'month_name' => $firstItem->month_name,
                    'area_type' => $firstItem->area_type,
                    'cooking_area_type' => $firstItem->cooking_area_type,
                    'amount' => $items->sum('amount')
                ];
            });

        return $months->map(function($month) use ($request, $results) {
            $monthData = $results->get($month);
            return [
                'month_name' => $month,
                'area_type' => $request->area_type ?? ($monthData['area_type'] ?? ''),
                'cooking_area_type' => $request->cooking_area_type ?? ($monthData['cooking_area_type'] ?? ''),
                'amount' => $monthData['amount'] ?? 0,
            ];
        });
    }

    public function getTotalKTVCustomers($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('total_ktv_customers')
            ->select('month_name', DB::raw('SUM(total_ktv_customer) as amount'))
            ->groupBy('month_name')
            ->pluck('amount', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'amount' => $results[$month] ?? 0,
        ]);
    }

    public function getTotalKTVSessions($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('monthly_total_ktv_sessions')
            ->select('month_name', 'total_ktv_sessions')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year)
            ->pluck('total_ktv_sessions', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'total_ktv_sessions' => $results[$month] ?? 0,
        ]);
    }

    public function getTotalKTVRoomCharges($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('monthly_total_ktv_room_charges')
            ->select('month_name', 'total_ktv_room_charges')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year)
            ->pluck('total_ktv_room_charges', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'total_ktv_room_charges' => $results[$month] ?? 0,
        ]);
    }

    public function getTotalKTVSales($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('monthly_total_ktv_sales')
            ->select('month_name', 'total_ktv_sales')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year)
            ->pluck('total_ktv_sales', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'total_ktv_sales' => $results[$month] ?? 0,
        ]);
    }

    public function getKTVTraining($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('monthly_total_ktv_training')
            ->select('month_name', 'total_ktv_training')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year)
            ->pluck('total_ktv_training', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'total_ktv_training' => $results[$month] ?? 0,
        ]);
    }

    public function getWaiterSale($request)
    {
        dd("hello");
    }

    public function getBarTotalExpense($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('bar_department_expenses')
            ->select('month_name', 'bar_total_expense')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year)
            ->pluck('bar_total_expense', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'bar_total_expense' => $results[$month] ?? 0,
        ]);
    }

    public function getTargetActualMenuSales($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('target_actual_monthly_menu_sales as tamms')
        ->join('menus as mn', 'tamms.menu_id', '=', 'mn.id')
        ->join('areas as ar', 'tamms.area_id', '=', 'ar.id')
        ->select(
            'tamms.id as target_id',
            'tamms.year as target_year',
            'tamms.month_number as target_month_number',
            'tamms.month_name as target_month_name',
            'tamms.menu_id as target_menu_id',
            'mn.name as menu_name',
            'ar.name as area_name',
            'tamms.target_quantity as target_qty',
            'tamms.actual_quantity as actual_qty',
            'tamms.target_sales_amount as target_amount',
            'tamms.actual_sales_amount as actual_amount',
            'tamms.achieved_percentage as percentage_hit'
        )
        ->where('tamms.year', $request->year?? now()->year)
        ->where(function ($query) use ($request) {
            if($request->month_name)
                $query->where('tamms.month_name', $request->month_name);
            if($request->month)
                $query->where('tamms.month_number', $request->month);
        })
        ->get();

        return $results;
    }

    public function getDailyAreaSalesVolumeByStaff($request)
    {
        if(!$request->start_date){
            ResponseMessage('start_date must be provided', 400);
        }
        if(!$request->end_date){
            ResponseMessage('end_date must be provided', 400);
        }
        $areaId = $request->area_id?? Area::where('name','like','%Sky%')->first()->id;

        $results = DB::table('daily_area_sales_volume_by_staff as dasvs')
        ->join('staff as st', 'dasvs.staff_id', '=', 'st.id')
        ->join('areas as ar', 'dasvs.area_id', '=', 'ar.id')
        ->whereBetween('dasvs.work_date', [$request->start_date, $request->end_date])
        ->where('dasvs.area_id',$areaId)
        ->selectRaw('
            dasvs.id AS target_id,
            dasvs.year AS target_year,
            dasvs.month_number AS target_month_number,
            dasvs.month_name AS target_month_name,
            dasvs.staff_id AS staff_id,
            st.name AS staff_name,
            ar.id AS area_id,
            ar.name AS area_name,
            COALESCE(SUM(dasvs.total_amount), 0) AS total_amount,
            COALESCE(SUM(dasvs.total_pax), 0) AS total_pax,
            COALESCE(SUM(dasvs.per_pax), 0) AS total_par_pex
        ')
        ->groupByRaw('
            dasvs.work_date,
            dasvs.staff_id,
            st.name,
            ar.id,
            ar.name,
            dasvs.year,
            dasvs.month_number,
            dasvs.month_name,
            dasvs.id
        ')
        ->orderBy('dasvs.work_date')
        ->orderBy('st.name')
        ->get();

        return $results;
    }
}
