<?php

namespace App\Repositories\FinancialReport;

use App\Models\CashbookBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialRepository implements FinancialInterface
{
    public function CashFlowStatementOriginal($request) //original
    {
        // $month = $request->month != null ? $request->month : now()->month;
        $current = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();
        $receiptSubAccountCode = ['2-1000', '2-1050', '2-2000', '4-3000', '5-0000', '5-1010', '5-1000', '4-4000', '3-1000'];
        $receptAceiptAction = 'debit';

        $paymentSubAccountCode = ['1-1000', '1-1100', '2-1000', '2-1020', '2-1050', '2-3000', '2-4000', '3-2000', '4-1000', '4-2000', '4-3000', '4-4000', '6-0000', '6-2000', '6-3000', '6-4000', '6-5000', '6-6000', '6-7000', '6-8000', '6-9000', '3-1000'];
        $paymentAceiptAction = 'credit';

        $totalDebitAmount = DB::table('sub_accounts')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($receptAceiptAction) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $receptAceiptAction);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $receiptSubAccountCode)
            ->where('transactions.is_confirmed', 1)
            ->whereMonth('ledgers.created_at', '<=', $previousMonth)
            ->sum('ledgers.value');
        $totalCreditAmount = DB::table('sub_accounts')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($paymentAceiptAction) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $paymentAceiptAction);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $paymentSubAccountCode)
            ->where('transactions.is_confirmed', 1)
            ->whereMonth('ledgers.created_at', '<=', $previousMonth)
            ->sum('ledgers.value');

        $debitResults = DB::table('sub_accounts')
            ->select('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($receptAceiptAction, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $receptAceiptAction)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $receiptSubAccountCode)
            ->groupBy('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code', 'accounts.id', 'accounts.name', 'accounts.account_code')
            ->selectRaw('
        SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) as total_amount,
        accounts.id as account_id,
        sub_accounts.account_code as code,
        accounts.name as account_name,
        accounts.account_code as account_code,
        SUM(CASE WHEN transactions.is_confirmed = 1 THEN ledgers.value ELSE 0 END) as amount,
        "debit" as type', [$receptAceiptAction])
            ->orderByRaw("FIELD(sub_accounts.account_code, '" . implode("','", $receiptSubAccountCode) . "')")
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id,
                    'total_amount' => $group->sum('total_amount'),
                    'name' => $group->first()->name,
                    'account_code' => $group->first()->code,
                    'type' => 'debit',
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
        $creditResults = DB::table('sub_accounts')
            ->select('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($paymentAceiptAction, $current) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->where('ledgers.action', '=', $paymentAceiptAction)
                    ->whereMonth('ledgers.created_at', $current->month)
                    ->whereYear('ledgers.created_at', $current->year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
        // ->whereMonth('ledgers.created_at', $current->month)
            ->whereIn('sub_accounts.account_code', $paymentSubAccountCode)
            ->groupBy('sub_accounts.id', 'sub_accounts.name', 'sub_accounts.account_code', 'accounts.id', 'accounts.name', 'accounts.account_code')
            ->selectRaw('
        SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = ? THEN ledgers.value ELSE 0 END) as total_amount,
        accounts.id as account_id,
        accounts.name as account_name,
        accounts.account_code as account_code,
        SUM(CASE WHEN transactions.is_confirmed = 1 THEN ledgers.value ELSE 0 END) as amount,
        "credit" as type', [$paymentAceiptAction])
            ->orderByRaw("FIELD(sub_accounts.account_code, '" . implode("','", $paymentSubAccountCode) . "')")
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id,
                    'total_amount' => $group->sum('total_amount'),
                    'name' => $group->first()->name,
                    'account_code' => $group->first()->account_code,
                    'type' => 'credit',
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

        $totalDebitAmount = array_sum(array_column($debitResults, 'total_amount'));
        $totalCreditAmount = array_sum(array_column($creditResults, 'total_amount'));

        //cash book opening
        $previousClosingBalance = CashbookBalance::where('year', $previousMonth->year)
            ->where('month', $previousMonth->month)
            ->value('closing_balance') ?? 0;

        //cashbook  closing
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
        $closingBalance = ((int) $previousClosingBalance + $totalCashbookDebitAmount) - $totalCashbookCreditAmount;
        #end cashbook
        $results = array_merge($debitResults, $creditResults);
        return [
            'opening_balance' => $previousClosingBalance,
            'closing_balance' => $closingBalance,
            'total_receipt_amount' => $totalDebitAmount,
            'total_payment_amount' => $totalCreditAmount,
            'cash_flow_statement' => $results,
        ];
        // return $results;
    }

    public function CashFlowStatement($request) //update
    {
        // dd($request->all());
        $current = (isset($request->date)|| $request->date!=null) ? Carbon::parse($request->date):Carbon::now();
        $previousMonth = $current->copy()->subMonth();
        // Define Sub Account Codes and Actions
        $receiptSubAccountCode = ['2-1000', '2-1050', '2-2000', '4-3000', '5-0000', '5-1010', '5-1000', '4-4000', '3-1000'];
        $paymentSubAccountCode = ['1-1000', '1-1100', '2-1000', '2-1020', '2-1050', '2-3000', '2-4000', '3-2000', '4-1000', '4-2000', '4-3000', '4-4000', '6-0000', '6-2000', '6-3000', '6-4000', '6-5000', '6-6000', '6-7000', '6-8000', '6-9000', '3-1000'];

        // Calculate Total Amounts
        // $totalDebitAmount = $this->calculateTotalAmount($receiptSubAccountCode, 'debit', $previousMonth);
        // $totalCreditAmount = $this->calculateTotalAmount($paymentSubAccountCode, 'credit', $previousMonth);

        // Get Debit and Credit Results
        $debitResults = $this->getTransactionResults($receiptSubAccountCode, 'debit', $current);
        $creditResults = $this->getTransactionResults($paymentSubAccountCode, 'credit', $current);
        $currentTotalDebitAmount = array_sum(array_column($debitResults, 'total_amount'));
        $currentTotalCreditAmount = array_sum(array_column($creditResults, 'total_amount'));
        // Calculate Cashbook Balances
        $previousClosingBalance = $this->getPreviousClosingBalance($previousMonth);
        $closingBalance = $this->calculateClosingBalance($current, $previousClosingBalance);

        // Merge Results and Return
        $results = array_merge($debitResults, $creditResults);
        $cash_in_out_flow = $currentTotalDebitAmount - $currentTotalCreditAmount;
        return [
            'opening_balance' => $previousClosingBalance,
            'closing_balance' => $closingBalance,
            'total_receipt_amount' => $currentTotalDebitAmount,
            'total_payment_amount' => $currentTotalCreditAmount,
            'cash_in_out_flow' => $cash_in_out_flow,
            'cash_flow_statement' => $results,
        ];
    }

    private function calculateTotalAmount($subAccountCodes, $action, $month)
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

    private function getTransactionResults($subAccountCodes, $action, $current)
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

    private function getPreviousClosingBalance($month)
    {
        return CashbookBalance::where('year', $month->year)
            ->where('month', $month->month)
            ->sum('closing_balance') ?? 0;
    }

    private function calculateClosingBalance($current, $previousClosingBalance)
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
}
