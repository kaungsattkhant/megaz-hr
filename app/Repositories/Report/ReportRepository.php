<?php
namespace App\Repositories\Report;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportInterface
{
    private function orderByMonthName($query)
    {
        return $query->orderByRaw("FIELD(month_name, 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec')");
    }
    public function getBarForSky($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('bar_monthly_sale')
            ->select('month_name', DB::raw('SUM(total) as amount'))
            ->groupBy('month_name')
            ->pluck('amount', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'amount' => $results[$month] ?? 0,
        ]);
    }

    public function getTotalKTVCustomers($request)
    {
        $results = DB::table('total_ktv_customers')
            ->select('month_name', DB::raw('SUM(total_ktv_customer) as amount'))
            ->groupBy('month_name');
        return $this->orderByMonthName($results)->get();
    }

    public function getTotalKTVSessions($request)
    {
        $results = DB::table('monthly_total_ktv_sessions')
            ->select('month_name', 'total_ktv_sessions')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year);
        return $this->orderByMonthName($results)->get();
    }

    public function getTotalKTVRoomCharges($request)
    {
        $results = DB::table('monthly_total_ktv_room_charges')
            ->select('month_name', 'total_ktv_room_charges')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year);
        return $this->orderByMonthName($results)->get();
    }

    public function getTotalKTVSales($request)
    {
        $results = DB::table('monthly_total_ktv_sales')
            ->select('month_name', 'total_ktv_sales')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year);
        return $this->orderByMonthName($results)->get();
    }

    public function getKTVTraining($request)
    {
        $results = DB::table('monthly_total_ktv_training')
            ->select('month_name', 'total_ktv_training')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year);
        return $this->orderByMonthName($results)->get();
    }

    public function getWaiterSale($request)
    {
        dd("hello");
    }

}