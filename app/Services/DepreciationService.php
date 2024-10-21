<?php

namespace App\Services;

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
}