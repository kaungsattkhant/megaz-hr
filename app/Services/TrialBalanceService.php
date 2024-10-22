<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\SubAccount;
use Illuminate\Support\Facades\DB;
use App\Models\AssetDepreciationBalance;

class TrialBalanceService
{

    public function getTrialBalanceResults($subAccountCodes, $action, $current, $report_type)
    {
        $results = DB::table('accounts')
            ->leftJoin('ledgers', function ($join) use ($action, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $action)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('accounts.account_code', $subAccountCodes)
            ->groupBy('accounts.id', 'accounts.name', 'accounts.account_code')
            ->selectRaw('
        accounts.id,
        accounts.name as account_name,
        accounts.account_code as account_code,
        SUM(CASE WHEN transactions.is_confirmed = 1 THEN ledgers.value ELSE 0 END) as amount,
        ? as type
    ', [$action]) // Ensure only one placeholder is used for binding
            ->orderByRaw("FIELD(accounts.account_code, '" . implode("','", $subAccountCodes) . "')")
            ->get();

        if ($report_type == 'credit_balance') {
            return $this->finalResult($results);
        }
        return $results;

    }

    public function getResultBySubAccountCode($subAccountCodes, $action, $current, $report_type)
    {
        $results = DB::table('accounts')
            ->join('sub_accounts', 'accounts.sub_account_id', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($action, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $action)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $subAccountCodes)
            ->groupBy('accounts.id', 'accounts.name', 'accounts.account_code')
            ->selectRaw('
            accounts.name,
    accounts.account_code as account_code,
    SUM(CASE WHEN transactions.is_confirmed = 1 THEN ledgers.value ELSE 0 END) as amount,
    ? as type
', [$action]) // Ensure only one placeholder is used for binding
            ->orderByRaw("FIELD(accounts.account_code, '" . implode("','", $subAccountCodes) . "')")
            ->get();
        return $results;
    }

    public function getTotalBySubAccountCode($subAccountCodes, $action, $current, $report_type)
    {
        return DB::table('sub_accounts')
            // ->select('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($action, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $action)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $subAccountCodes)
            ->groupBy('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->selectRaw('
            sub_accounts.name,
                    CAST(SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) AS SIGNED INTEGER) as total_amount,
                sub_accounts.account_code as code,
                ? as type
            ', [$action, $action])
            // SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) as total_amount,
            ->orderByRaw("FIELD(sub_accounts.account_code, '" . implode("','", $subAccountCodes) . "')")
            ->get();
    }

    public function getTotalResultBySubAccountCode($subAccountCodes, $action, $current, $report_type)
    {
        return DB::table('sub_accounts')
            // ->select('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($action, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $action)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $subAccountCodes)
            // ->groupBy('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->selectRaw('
                SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) as total_amount,
                ? as type
            ', [$action, $action])
            ->orderByRaw("FIELD(sub_accounts.account_code, '" . implode("','", $subAccountCodes) . "')")
            ->get();
    }
    public function finalResult($results)
    {
        // return $results;
        $foodIncomeCodes = ['5-0001', '5-0101'];
        $beverageIncomeCodes = ['5-0002', '5-0102'];
        $roomChargeCodes = ['5-0003', '5-0103'];
        $retainedEarningsCodes = ['3-1020'];
        $existingCapitalCodes = ['3-1010'];
        $loanCode = ['4-1001'];

        // $foodIncome = $results->whereIn('account_code', $foodIncomeCodes)->sum('amount');
        // $beverageIncome = $results->whereIn('account_code', $beverageIncomeCodes)->sum('amount');
        // $roomCharge = $results->whereIn('account_code', $roomChargeCodes)->sum('amount');
        // $retainedEarning = $results->whereIn('account_code', $retainedEarningsCodes)->sum('amount');
        // $existingCapital = $results->whereIn('account_code', $existingCapitalCodes)->sum('amount');
        // $loan = $results->whereIn('account_code', $loanCode)->sum('amount');

        // $finalResult = new \stdClass();
        // $finalResult->food_income = $foodIncome;
        // $finalResult->beverage_income = $beverageIncome;
        // $finalResult->room_charge = $roomCharge;
        // $finalResult->capital = $retainedEarning;
        // $finalResult->retained_earnings = $existingCapital;
        // $finalResult->loan = $loan;
        $finalResult = [];

        // Add each entry with 'name' and 'amount' to the final result
        if ($results->whereIn('account_code', $foodIncomeCodes)->isNotEmpty()) {
            $finalResult[] = [
                'name' => 'Food Income',
                'amount' => $results->whereIn('account_code', $foodIncomeCodes)->sum('amount')
            ];
        }


        if ($results->whereIn('account_code', $beverageIncomeCodes)->isNotEmpty()) {
            $finalResult[] = [
                'name' => 'Beverage Income',
                'amount' => $results->whereIn('account_code', $beverageIncomeCodes)->sum('amount')
            ];
        }


        if ($results->whereIn('account_code', $roomChargeCodes)->isNotEmpty()) {
            $finalResult[] = [
                'name' => 'Room Charges',
                'amount' => $results->whereIn('account_code', $roomChargeCodes)->sum('amount')
            ];
        }

        if ($results->whereIn('account_code', $retainedEarningsCodes)->isNotEmpty()) {
            $finalResult[] = [
                'name' => 'Retained Earnings',
                'amount' => $results->whereIn('account_code', $retainedEarningsCodes)->sum('amount')
            ];
        }


        if ($results->whereIn('account_code', $existingCapitalCodes)->isNotEmpty()) {
            $finalResult[] = [
                'name' => 'Existing Capital',
                'amount' => $results->whereIn('account_code', $existingCapitalCodes)->sum('amount')
            ];
        }

        if ($results->whereIn('account_code', $loanCode)->isNotEmpty()) {
            $finalResult[] = [
                'title' => 'Long Term Liabilities',
                'name' => 'Loan',
                'amount' => $results->whereIn('account_code', $loanCode)->sum('amount')
            ];
        }


        return $finalResult;
    }

    public function getAssetBookValue($date, $account_code, $account_name)
    {
        $date = Carbon::parse($date);
        $month = Carbon::parse($date)->format('n');
        $year = Carbon::parse($date)->format('Y');

        $book_value = AssetDepreciationBalance::join('assets', 'asset_depreciation_balances.asset_id', '=', 'assets.id')
            ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
            ->join('accounts as account_depreciation', 'assets.third_depreciation_account_id', '=', 'account_depreciation.id')
            ->join('sub_accounts', 'main_account.sub_account_id', '=', 'sub_accounts.id')
            ->where('month', $month)
            ->where('year', $year)
            ->where('sub_accounts.account_code', $account_code)
            // ->select(
            //     'sub_accounts.name',
            //     'sub_accounts.account_code',
            //     // DB::raw('SUM(asset_depreciation_balances.book_value) as book_value'),
            //     DB::raw('COALESCE(SUM(asset_depreciation_balances.book_value), 0) as book_value') // Use COALESCE here
            // )
            ->selectRaw('sub_accounts.name, sub_accounts.account_code, COALESCE(SUM(asset_depreciation_balances.book_value), 0) as amount')
            ->groupBy('sub_accounts.account_code')
            ->first();
        if (!$book_value) {
            // Return default structure if no data is found
            $book_value = (object) [
                'name' => $account_name, // Fallback name if no data is found
                'account_code' => $account_code,        // Use the provided account_code
                'amount' => 0,
                'type' => 'credit',
            ];
        }
        return $book_value;
    }
}