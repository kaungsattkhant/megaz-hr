<?php
namespace App\Repositories\Report;

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

}