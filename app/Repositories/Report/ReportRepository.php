<?php
namespace App\Repositories\Report;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportInterface
{
    public function getBarForSky($request)
    {

        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);

        $sales = DB::table('bar_monthly_sale')
            ->select('month_name', DB::raw('SUM(total) as amount'))
            ->groupBy('month_name')
            // <<<<<<< HEAD
            ->pluck('amount', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'amount' => $sales[$month] ?? 0,
        ]);

    }

    public function getTotalKTVCustomers($request)
    {
        $results = DB::table('total_ktv_customers')
            ->select('month_name', DB::raw('SUM(total_ktv_customer) as amount'))
            ->groupBy('month_name')
            ->orderByRaw("FIELD(month_name, 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec')")
            ->get();
        return $results;
    }

}