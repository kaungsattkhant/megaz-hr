<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\CashbookBalance;
use Illuminate\Support\Facades\DB;

class CashFlowService
{
    public function calculateTotalAmount($subAccountCodes, $action, $month)
    {
        return DB::table('sub_accounts')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($action, $month) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $action)
                    ->whereMonth('ledgers.created_at', $month->month)
                    ->whereYear('ledgers.created_at', $month->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $subAccountCodes)
            ->where('transactions.is_confirmed', 1)
            ->sum('ledgers.value');
    }

    public function getTransactionResults($subAccountCodes, $action, $current)
    {
        return DB::table('sub_accounts')
            ->select('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($action, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $action)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $subAccountCodes)
            ->groupBy('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code', 'accounts.id', 'accounts.name', 'accounts.account_code')
            ->selectRaw('
                SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) as total_amount,
                accounts.id as account_id,
                sub_accounts.account_code as code,
                accounts.name as account_name,
                accounts.account_code as account_code,
                SUM(CASE WHEN transactions.is_confirmed = 1 THEN ledgers.value ELSE 0 END) as amount,
                ? as type
            ', [$action, $action])
            ->orderByRaw("FIELD(sub_accounts.account_code, '" . implode("','", $subAccountCodes) . "')")
            ->get()
            ->groupBy('id')
            ->map(function ($group) use ($action) {
                return [
                    'id' => $group->first()->id,
                    'total_amount' => $group->sum('total_amount'),
                    'name' => $group->first()->name,
                    'account_code' => $group->first()->code,
                    'type' => $action,
                    'accounts' => $group->map(function ($item) {
                        return [
                            'id' => $item->account_id,
                            'amount' => $item->amount,
                            'name' => $item->account_name,
                            'account_code' => $item->account_code,
                        ];
                    })->sortBy('id')->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();
    }

    public function getPreviousClosingBalance($month)
    {
        return CashbookBalance::where('year', $month->year)
            ->where('month', $month->month)
            ->sum('closing_balance') ?? 0;
    }

    public function calculateClosingBalance($current, $previousClosingBalance)
    {
        $totals = DB::table('ledgers')
            ->join('accounts', 'ledgers.account_id', 'accounts.id')
            ->join('sub_accounts', 'accounts.sub_account_id', 'sub_accounts.id')
            ->select(
                DB::raw('SUM(CASE WHEN action = "debit" THEN value ELSE 0 END) as total_cashbook_debit_amount'),
                DB::raw('SUM(CASE WHEN action = "credit" THEN value ELSE 0 END) as total_cashbook_credit_amount')
            )
            ->whereYear('ledgers.created_at', $current->year)
            ->whereMonth('ledgers.created_at', $current->month)
            ->where('sub_accounts.account_code', config('common.cash_code'))
            ->first();

        $totalCashbookDebitAmount = (int) $totals->total_cashbook_debit_amount;
        $totalCashbookCreditAmount = (int) $totals->total_cashbook_credit_amount;

        return $previousClosingBalance + $totalCashbookDebitAmount - $totalCashbookCreditAmount;
    }

    public function getIndirectCashFlowStatement($subAccountCodes, $type, $current)
    {
        $action=$type=='debit' ?'debit': 'credit' ;
        $queryResult = DB::table('sub_accounts')
            ->select('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
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
            SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) as total_amount,
            sub_accounts.account_code as code,
            SUM(CASE WHEN transactions.is_confirmed = 1 THEN ledgers.value ELSE 0 END) as amount,
            ? as type
        ', [$action, $action])
            ->orderByRaw("FIELD(sub_accounts.account_code, '" . implode("','", $subAccountCodes) . "')")
            ->get();
        if ($type == 'debit') {
            return $this->cashInFlowResult($queryResult);
        }
        if ($type == 'credit_cash_out_flow') {
            return $this->cashOutFlowResult($queryResult);
        }
        if ($type == 'credit_fixed_asset') {
            return $this->fixedAssetResult($queryResult);
        }
    }

    public function cashInFlowResult($queryResult)
    {
        $mergedResult = [];
        $cashSaleTotalAmount = 0;
        $mapping = [
            '2-1050' => 'Cash Collection From Other Receivable',
            '2-2000' => 'Credit Collection From Customer',
            '5-1000' => 'Other Income from Operating',
            '4-3000' => 'Deposit Receipt',
            '4-4000' => 'Cash Collection From Other Payables',
        ];
        foreach ($queryResult as $result) {
            if (in_array($result->account_code, ['5-0000', '5-0100'])) {
                // Accumulate the total amount for Cash Sale (RT and KTV)
                $cashSaleTotalAmount += (int) $result->total_amount;
            } else if (isset($mapping[$result->code])) {
                // Handle mapped names
                $mergedResult[] = (object) [
                    'account_name' => $result->name,
                    'name' => $mapping[$result->code],
                    'amount' => (int) $result->total_amount,
                    'type' => 'debit',
                ];
            }
        }

        array_unshift($mergedResult, (object) [
            'account_name' => 'Cash Sale',
            'name' => 'Cash Sale',
            'amount' => $cashSaleTotalAmount,
            'type' => 'debit',
        ]);

        return $mergedResult;
    }

    public function cashOutFlowResult($queryResult)
    {
        $mergedResult = [];
        $cashSaleTotalAmount = 0;
        $purchaseFA = 0;
        $mapping = [
            '4-2000' => 'Cash Paid to Creditor',
            '2-3000' => 'Prepaid(Rent & Other)',
            '2-1050' => 'Cash Paid to Other Receivables',
            '4-4000' => 'Cash Paid To Other Payable',
            '4-3000' => 'Deposit Paid',
            '6-2000' => 'Selling & Destribution',
            '6-3000' => 'Operation Exps.',
            '6-4000' => 'Admin & General',
            '6-5000' => 'Pay & Related Exps',
            '6-6000' => 'Monthly Tax',
            '6-7000' => 'Year Tax',
            '6-9000' => 'Rental Fee',
        ];
        foreach ($queryResult as $result) {
            if (in_array($result->account_code, ['2-1020', '6-0000'])) {
                // Accumulate the total amount for Cash Sale (RT and KTV)
                $cashSaleTotalAmount += (int) $result->total_amount;
            }
            // if (in_array($result->account_code, ['1-1000', '1-1100'])) {
            //     // Accumulate the total amount for Cash Sale (RT and KTV)
            //     $purchaseFA += (int) $result->total_amount;
            // } 
            else if (isset($mapping[$result->code])) {
                // Handle mapped names
                $mergedResult[] = (object) [
                    'account_name' => $result->name,
                    'name' => $mapping[$result->code],
                    'amount' => (int) $result->total_amount,
                    'type' => 'credit',
                ];
            }
        }

        array_unshift($mergedResult, (object) [
            'account_name' => 'Inventory Purchase',
            'name' => 'Inventory Purchase ',
            'amount' => $cashSaleTotalAmount,
            'type' => 'credit',
        ]);
        // array_unshift($mergedResult, (object) [
        //     'account_name' => 'Purchase FA',
        //     'name' => 'Purchase FA ',
        //     'amount' => $purchaseFA,
        //     'type' => 'credit',
        // ]);

        return $mergedResult;
    }

    public function fixedAssetResult($queryResult)
    {
        $mergedResult = [];
        $totalAmount = 0;
        foreach ($queryResult as $result) {
            if (in_array($result->account_code, ['1-1000', '1-1100'])) {
                // Accumulate the total amount for Cash Sale (RT and KTV)
                $totalAmount += (int) $result->total_amount;
            } else if (isset($mapping[$result->code])) {
                // Handle mapped names
                $mergedResult[] = (object)[
                    'account_name' => $result->name,
                    'name' => $mapping[$result->code],
                    'amount' => (int) $result->total_amount,
                    'type' => 'credit',
                ];
            }
        }

        array_unshift($mergedResult, (object) [
            'account_name' => 'Fixed Asset Tengible & Untengible',
            'name' => ' Purchase FA ',
            'amount' => $totalAmount,
            'type' => 'credit',
        ]);

        return $mergedResult;
    }
    
}
