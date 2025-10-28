<?php
namespace App\Repositories\Report;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportInterface
{
    public function getBarForSky($request)
    {
        $areaType = $request->area_type ?? null;
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('bar_monthly_sale')
            ->select('year','month_name', 'area_type', 'cooking_area_type', DB::raw('SUM(total) as amount'))
            ->when(isset($areaType), function($query) use ($areaType) {
                return $query->where('area_type', $areaType);
            })
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
            if(!$monthData){
                return [
                    'month_name' => $month,
                    'area_type' => '',
                    'cooking_area_type' => '',
                    'amount' => 0,
                ];
            }
            return [
                'month_name' => $month,
                'area_type' => $monthData['area_type'] ?? '',
                'cooking_area_type' => $monthData['cooking_area_type'] ?? '',
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

    public function getKitchenForSktyAndKtv($request)
    {
        $year = $request->year ?? now()->year;
        $areaType = $request->area_type ?? null;
        
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $query = DB::table('kitchen_monthly_sales')
            ->select('year','month_name', 'area_type', 'cooking_area_type', DB::raw('SUM(total_kitchen_sale) as total_kitchen_sale'))
            ->where('year', $year);
            if(isset($areaType)){
                $query->where('area_type', $areaType);
            }
            $results = $query->groupBy('year','month_name', 'area_type', 'cooking_area_type')
            ->get()
            ->groupBy('month_name')
            ->map(function($items) {
                $firstItem = $items->first();
                return [
                    'year' => $firstItem->year,
                    'month_name' => $firstItem->month_name,
                    'area_type' => $firstItem->area_type,
                    'cooking_area_type' => $firstItem->cooking_area_type,
                    'total_kitchen_sale' => $items->sum('total_kitchen_sale')
                ];
            });

        return $months->map(function($month) use ($request, $results) {
            $monthData = $results->get($month);
            if(!$monthData){
                return [
                    'month_name' => $month,
                    'area_type' => '',
                    'cooking_area_type' => '',
                    'total_kitchen_sale' => 0,
                ];
            }
            return [
                'month_name' => $month,
                'area_type' => $monthData['area_type'] ?? '',
                'cooking_area_type' => $monthData['cooking_area_type'] ?? '',
                'total_kitchen_sale' => $monthData['total_kitchen_sale'] ?? 0,
            ];
        });
    }


    public function getKitchenTotalExpense($request)
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('kitchen_expenses')
            ->select('month_name', 'kitchen_total_expense')
            ->groupBy('month_name')
            ->where('year', $request->year ?? now()->year)
            ->pluck('kitchen_total_expense', 'month_name');
        return $months->map(fn($month) => [
            'month_name' => $month,
            'kitchen_total_expense' => $results[$month] ?? 0,
        ]);
    }

    public function getMonthlyKitchenMenuTotal($request)
    {
        $year = $request->year ?? now()->year;
        $areaType = $request->area_type ?? null;
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $menuItems = DB::table('monthly_kitchen_menus')   
            ->where('year', $year)
            ->when($areaType, function($query) use ($areaType) {
                return $query->where('area_type', $areaType);
            })
            ->select('menu_name')
            ->distinct()
            ->pluck('menu_name');
            $result = $menuItems->map(function($menuName) use ($year, $areaType) {
                $menuData = [
                    'menu_name' => $menuName
                ];
                $monthlyData = DB::table('monthly_kitchen_menus')
                    ->where('year', $year)
                    ->where('menu_name', $menuName)
                    ->when($areaType, function($query) use ($areaType) {
                        return $query->where('area_type', $areaType);
                    })
                    ->get()
                    ->keyBy('month_name');
                foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month) {
                    $menuData[$month] = $monthlyData->get($month)->total_menu_sale ?? 0;
                }
                
                return $menuData;
            });
            
            return $result;
    }

}