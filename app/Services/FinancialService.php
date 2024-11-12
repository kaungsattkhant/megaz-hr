<?php

namespace App\Services;

use App\Models\StaffBalance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class FinancialService
{
    public function getMonth($year,$currentMonth)
    {
        $monthsOfYear = collect(range(1, $currentMonth))->map(function ($month) use ($year) {
            $date = Carbon::create($year, $month, 1);
            return [
                'month_number' => $month,
                'month_name' => $date->format('F'),
                'date' => $date->format('Y-m-01'),
            ];
        });
        return $monthsOfYear;
    }
    public function getClosingBalance($model,$year)
    {
        $year = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthsOfYear=$this->getMonth($year,$currentMonth);
        $prepaidData = DB::table($model)
            ->where('year', $year)
            ->where('month', '<=', $currentMonth) // Only include months up to the current month
            ->select(
                'month',
                DB::raw("DATE_FORMAT(CONCAT(year, '-', LPAD(month, 2, '0'), '-01'), '%Y-%m-%d') as date"),
                DB::raw('closing_balance as value')
            )
            ->get();
        $prepaidBalances = $monthsOfYear->map(function ($month) use ($prepaidData) {
            $data = $prepaidData->firstWhere('month', $month['month_number']);

            return [
                'month' => $month['month_name'],
                'date' => $month['date'],
                'value' => $data ? $data->value : 0,
            ];
        });
        return $prepaidBalances;
    }

    public function getReceivableBalances($year,$month){
        $year = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlyBalances = collect(range(1, $currentMonth))->map(function ($month) use ($year) {
            $date = Carbon::create($year, $month, 1);
        
            // Calculate the sum of 'ar' and 'ar_paid' amounts for the year up to the current month
            $arTotal = DB::table('account_receivables')
                ->whereYear('date_time', $year)
                ->whereMonth('date_time', '<=', $month)
                ->where('type', 'ar')
                ->sum('amount');
            
            $arPaidTotal = DB::table('account_receivables')
                ->whereYear('date_time', $year)
                ->whereMonth('date_time', '<=', $month)
                ->where('type', 'ar_paid')
                ->sum('amount');
            
            // Calculate closing balance as the difference
            $closingBalance = $arTotal - $arPaidTotal;
        
            return [
                'month_name' => $date->format('F'),
                'date' => $date->format('Y-m-01'),
                'value' => $closingBalance,
            ];
        });
        return $monthlyBalances;
    }

}