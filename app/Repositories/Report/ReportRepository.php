<?php

namespace App\Repositories\Report;

use DatePeriod;
use DateInterval;
use App\Models\Area;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportRepository implements ReportInterface
{
    public function getBarForSky($request)
    {
        $areaType = $request->area_type ?? null;
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $results = DB::table('bar_monthly_sale')
            ->select('year', 'month_name', 'area_type', 'cooking_area_type', DB::raw('SUM(total) as amount'))
            ->when(isset($areaType), function ($query) use ($areaType) {
                return $query->where('area_type', $areaType);
            })
            ->groupBy('year', 'month_name', 'area_type', 'cooking_area_type')
            ->get()
            ->groupBy('month_name')
            ->map(function ($items) {
                $firstItem = $items->first();
                return [
                    'year' => $firstItem->year,
                    'month_name' => $firstItem->month_name,
                    'area_type' => $firstItem->area_type,
                    'cooking_area_type' => $firstItem->cooking_area_type,
                    'amount' => $items->sum('amount')
                ];
            });

        return $months->map(function ($month) use ($request, $results) {
            $monthData = $results->get($month);
            if (!$monthData) {
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
            ->where('tamms.year', $request->year ?? now()->year)
            ->where(function ($query) use ($request) {
                if ($request->month_name)
                    $query->where('tamms.month_name', $request->month_name);
                if ($request->month)
                    $query->where('tamms.month_number', $request->month);
            })
            ->get();

        return $results;
    }

    public function getDailyAreaSalesVolumeByStaff($request)
    {
        if (!$request->start_date) {
            ResponseMessage('start_date must be provided', 400);
        }
        if (!$request->end_date) {
            ResponseMessage('end_date must be provided', 400);
        }
        $areaId = $request->area_id ?? Area::where('name', 'like', '%Sky%')->first()->id;

        $results = DB::table('daily_area_sales_volume_by_staff as dasvs')
            ->join('staff as st', 'dasvs.staff_id', '=', 'st.id')
            ->join('areas as ar', 'dasvs.area_id', '=', 'ar.id')
            ->whereBetween('dasvs.work_date', [$request->start_date, $request->end_date])
            ->where('dasvs.area_id', $areaId)
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
            COALESCE(SUM(dasvs.per_pax), 0) AS total_per_pax
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

    public function getKitchenForSktyAndKtv($request)
    {
        $year = $request->year ?? now()->year;
        $areaType = $request->area_type ?? null;

        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $query = DB::table('kitchen_monthly_sales')
            ->select('year', 'month_name', 'area_type', 'cooking_area_type', DB::raw('SUM(total_kitchen_sale) as total_kitchen_sale'))
            ->where('year', $year);
        if (isset($areaType)) {
            $query->where('area_type', $areaType);
        }
        $results = $query->groupBy('year', 'month_name', 'area_type', 'cooking_area_type')
            ->get()
            ->groupBy('month_name')
            ->map(function ($items) {
                $firstItem = $items->first();
                return [
                    'year' => $firstItem->year,
                    'month_name' => $firstItem->month_name,
                    'area_type' => $firstItem->area_type,
                    'cooking_area_type' => $firstItem->cooking_area_type,
                    'total_kitchen_sale' => $items->sum('total_kitchen_sale')
                ];
            });

        return $months->map(function ($month) use ($request, $results) {
            $monthData = $results->get($month);
            if (!$monthData) {
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
            ->when($areaType, function ($query) use ($areaType) {
                return $query->where('area_type', $areaType);
            })
            ->select('menu_name')
            ->distinct()
            ->pluck('menu_name');
        $result = $menuItems->map(function ($menuName) use ($year, $areaType) {
            $menuData = [
                'menu_name' => $menuName
            ];
            $monthlyData = DB::table('monthly_kitchen_menus')
                ->where('year', $year)
                ->where('menu_name', $menuName)
                ->when($areaType, function ($query) use ($areaType) {
                    return $query->where('area_type', $areaType);
                })
                ->get()
                ->keyBy('month_name');
            foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month) {
                $menuData[$month] = $monthlyData->get($month)->total_menu_sale ?? 0;
            }

            return $menuData;
        });

        return $result;
    }

    public function getBudgetAccountsCashflow(Request $request)
    {
        $yearMonth = explode("-", $request->month);
        $year = $yearMonth[0];
        $month = $yearMonth[1];
        $start = Carbon::create($year, $month, 1);
        $end   = $start->copy()->endOfMonth();

        $raw = DB::table('budget_accounts as ba')
            ->selectRaw('
            a.id AS account_id,
            a.name AS account,
            bp.priority,
            DATE(ba.date) AS day,
            SUM(ba.amount) AS total
        ')
            ->join('budget_priorities as bp', 'bp.id', '=', 'ba.budget_priority_id')
            ->join('accounts as a', function ($j) {
                $j->on('a.id', '=', 'ba.sub_account_id')
                    ->where('ba.sub_account_type', 'account');
            })
            ->where('ba.status', 'confirmed')
            ->whereBetween('ba.date', [$start, $end])
            ->groupBy('a.id', 'a.name', 'bp.priority', 'day')
            ->orderBy('bp.priority')
            ->orderBy('a.name')
            ->get();

        $dates = collect(
            new DatePeriod($start, new DateInterval('P1D'), $end->copy()->addDay())
        )->map(fn($d) => $d->format('Y-m-d'));

        // Group by account_id + priority
        $grouped = $raw->groupBy(function ($row) {
            return $row->account_id . '-' . $row->priority;
        });

        $final = [];

        foreach ($grouped as $rows) {

            $first = $rows->first();

            $entry = [
                'account_id' => $first->account_id,
                'account'    => $first->account,
                'priority'   => $first->priority,
                'dates'      => []
            ];

            // Initialize all dates to zero
            foreach ($dates as $date) {
                $entry['dates'][$date] = 0;
            }

            // Fill in actual totals
            foreach ($rows as $row) {
                $entry['dates'][$row->day] = $row->total;
            }

            $final[] = $entry;
        }
        return $final;
    }

    public function getSaleByArea($request)
    {

        $date = Carbon::parse($request->date);
        $sellingAreaId = $request->selling_area_id;
        $currentMonth = $date->month;
        $currentYear = $date->year;
        $months = collect(range(1, $currentMonth))->map(function ($m) use ($currentYear) {
            $date = Carbon::create($currentYear, $m, 1);

            return [
                'month'      => $date->format('m'),   // 01, 02, ...
                'month_name' => $date->format('M'),   // Jan, Feb, ...
                'year'       => $date->format('Y'),   // 2025
            ];
        });
        //     $rows = DB::table('sale_by_area')
        //         ->join('menus', 'sale_by_area.menu_id', '=', 'menus.id')
        //         ->selectRaw('
        //     menus.id as menu_id,
        //     menus.name as menu_name,
        //     DATE_FORMAT(sale_by_area.date_time, "%m") as month,
        //     DATE_FORMAT(sale_by_area.date_time, "%Y") as year,
        //     SUM(sale_by_area.total_foc) as total_foc,
        //     SUM(sale_by_area.total_sale_qty) as qty
        // ')
        //         ->whereYear('sale_by_area.date_time', $currentYear)
        //         ->whereMonth('sale_by_area.date_time', '<=', $currentMonth)
        //         ->where('sale_by_area.selling_area_id', $sellingAreaId)
        //         ->groupBy('menus.id', 'menus.name', 'month', 'year')
        //         ->get();
        //     $grouped = $rows->groupBy('menu_id')->map(function ($items) use ($months) {

        //         $byMonth = $items->keyBy('month');

        //         $data = $months->map(function ($tpl) use ($byMonth) {
        //             $row = $byMonth->get($tpl['month']);

        //             return [
        //                 'month'      => $tpl['month'],
        //                 'month_name' => $tpl['month_name'],
        //                 'year'       => $tpl['year'],
        //                 'total_foc'  => $row ? (int) $row->total_foc : 0,
        //                 'qty'        => $row ? (int) $row->qty : 0,
        //             ];
        //         });

        //         return [
        //             'name' => $items->first()->menu_name,
        //             'data' => $data->values(),
        //         ];
        //     })->values();

        $rows = DB::table('sale_by_area')
            ->join('menus', 'sale_by_area.menu_id', '=', 'menus.id')
            ->join('menu_categories', 'menus.menu_category_id', '=', 'menu_categories.id')
            ->selectRaw('
        menu_categories.id   as menu_category_id,
        menu_categories.name as category_name,
        menus.id        as menu_id,
        menus.name      as menu_name,
        DATE_FORMAT(sale_by_area.date_time, "%m") as month,
        DATE_FORMAT(sale_by_area.date_time, "%Y") as year,
        SUM(sale_by_area.total_foc) as total_foc,
        SUM(sale_by_area.total_sale_qty) as qty
    ')
            ->whereYear('sale_by_area.date_time', $currentYear)
            ->whereMonth('sale_by_area.date_time', '<=', $currentMonth)
            ->when($sellingAreaId, function ($query) use ($sellingAreaId) {
                $query->where('sale_by_area.selling_area_id', $sellingAreaId);
            })
            ->groupBy('menu_categories.id', 'menu_categories.name', 'menus.id', 'menus.name', 'month', 'year')
            ->get();

        $grouped = $rows
            ->groupBy('menu_category_id')
            ->map(function ($categoryItems) use ($months) {

                $categoryName = $categoryItems->first()->category_name;

                $items = $categoryItems
                    ->groupBy('menu_id')
                    ->map(function ($menus) use ($months) {

                        $byMonth = $menus->keyBy('month');

                        $data = $months->map(function ($tpl) use ($byMonth) {
                            $row = $byMonth->get($tpl['month']);

                            return [
                                'month'      => $tpl['month'],
                                'month_name' => $tpl['month_name'],
                                'year'       => $tpl['year'],
                                'total_foc'  => $row ? (int) $row->total_foc : 0,
                                'qty'        => $row ? (int) $row->qty : 0,
                            ];
                        });

                        return [
                            'name' => $menus->first()->menu_name,
                            'data' => $data->values(),
                        ];
                    })->values();

                return [
                    'category_name' => $categoryName,
                    'items'         => $items,
                ];
            })->values();


        return $grouped;
    }

    public function getMonthlyPackage($request)
    {
        Carbon::setTestNow(Carbon::parse('2026-01-30 00:00:00'));
        $currentDate = Carbon::parse(now())->subDays(1);
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $raw = Invoice::where('invoice_type', 'package')
            ->whereYear('invoice_date', $currentDate->year)
            ->selectRaw('
        package_id,
        MONTH(invoice_date) as month_number,
        COUNT(id) as total_package_count
    ')
            ->groupBy('package_id', 'month_number')
            ->get();
        $packages = Package::whereIn('id', $raw->pluck('package_id')->unique())->get()->keyBy('id');

        $result = $packages->map(function ($package) use ($raw) {
            // Start with 12 zeros (Jan → Dec)
            $months = array_fill(0, 12, 0);

            $raw->where('package_id', $package->id)->each(function ($row) use (&$months) {
                // month_number is 1–12, array index is 0–11
                $months[$row->month_number - 1] = (int) $row->total_package_count;
            });

            return [
                'id' => $package->id,
                'name' => $package->name,
                'package_count_by_month' => $months,
            ];
        })->values();

        return ['months' => $months, 'data' => $result,];
    }

    public function getSaleByAreaType($request)
    {
        
    }
}
