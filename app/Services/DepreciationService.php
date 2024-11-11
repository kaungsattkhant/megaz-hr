<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AssetDepreciationBalance;

class DepreciationService
{

    public function depreciationBalanceQuery($account_code, $month, $year)
    {
        $depreciationBalance = AssetDepreciationBalance::join('assets', 'asset_depreciation_balances.asset_id', '=', 'assets.id')
            ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
            ->join('accounts as account_depreciation', 'assets.third_depreciation_account_id', '=', 'account_depreciation.id')
            // ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
            ->join('sub_accounts', 'main_account.sub_account_id', '=', 'sub_accounts.id')
            ->where('month', $month)
            ->where('year', $year)
            ->where('sub_accounts.account_code', $account_code)
            ->select(
                DB::raw('SUM(asset_depreciation_balances.original_cost) as original_cost'),
                DB::raw('SUM(asset_depreciation_balances.addition_year_cost) as addition_year_cost'),
                DB::raw('SUM(asset_depreciation_balances.addition_year_depreciation) as addition_year_depreciation'),
                DB::raw('SUM(asset_depreciation_balances.total_cost) as total_cost'),
                DB::raw('SUM(asset_depreciation_balances.current_month_depreciation) as current_month_depreciation'),
                DB::raw('SUM(asset_depreciation_balances.total_depreciation) as total_depreciation'),
                DB::raw('SUM(asset_depreciation_balances.book_value) as book_value'),
                'main_account.name as name',
            )
            ->groupBy('main_account.id', 'account_depreciation.id')
            ->get();
        return $depreciationBalance;
    }
    public function depreciationBalanceQueryOfYear($account_code,$year){
 
    // $depreciationBalances = AssetDepreciationBalance::join('assets', 'asset_depreciation_balances.asset_id', '=', 'assets.id')
    // ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
    // ->join('sub_accounts', 'main_account.sub_account_id', '=', 'sub_accounts.id')
    // ->where('year', $year)
    // ->where('sub_accounts.account_code', $account_code)
    // ->select(
    //     'asset_depreciation_balances.month', // Group by month
    //     DB::raw("DATE_FORMAT(CONCAT(year, '-', LPAD(month, 2, '0'), '-01'), '%Y-%m-%d') as date"), // Format date
    //     DB::raw('SUM(asset_depreciation_balances.book_value) as value') // Sum across all assets for each month
    // )
    // ->groupBy('asset_depreciation_balances.month', 'asset_depreciation_balances.year')
    // ->orderBy('asset_depreciation_balances.month')
    // ->get();
    $year = Carbon::now()->year;
    $currentMonth = Carbon::now()->month;
    
    $monthsOfYear = collect(range(1, $currentMonth))->map(function ($month) use ($year) {
        $date = Carbon::create($year, $month, 1);
        return [
            'month_number' => $month,
            'month_name' => $date->format('F'),
            'date' => $date->format('Y-m-01'),
        ];
    });
    
    // Step 2: Retrieve the actual depreciation balances data, grouped by month
    $depreciationData = AssetDepreciationBalance::join('assets', 'asset_depreciation_balances.asset_id', '=', 'assets.id')
        ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
        ->join('sub_accounts', 'main_account.sub_account_id', '=', 'sub_accounts.id')
        ->where('year', $year)
        ->where('sub_accounts.account_code', $account_code)
        ->select(
            'asset_depreciation_balances.month',
            DB::raw("DATE_FORMAT(CONCAT(year, '-', LPAD(month, 2, '0'), '-01'), '%Y-%m-%d') as date"),
            DB::raw('SUM(asset_depreciation_balances.book_value) as value')
        )
        ->groupBy('asset_depreciation_balances.month', 'asset_depreciation_balances.year')
        ->orderBy('asset_depreciation_balances.month')
        ->get();
    
    // Step 3: Merge months with depreciation data, setting value to 0 if no data exists
    $depreciationBalances = $monthsOfYear->map(function ($month) use ($depreciationData) {
        $data = $depreciationData->firstWhere('month', $month['month_number']);
    
        return [
            'month' => $month['month_name'],
            'date' => $month['date'],
            'value' => $data ? $data->value : 0,
        ];
    });
    
    // Step 4: Return the results
    return $depreciationBalances;
    }
}