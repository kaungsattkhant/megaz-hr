<?php

namespace App\Repositories\FinancialReport;

use App\Models\CashbookBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\CashFlowService;
use App\Services\TrialBalanceService;

class FinancialRepository implements FinancialInterface
{
    protected $cashFlowService;
    protected $trialBalanceService;
    private $receiptSubAccountCode = ['2-1000', '2-1050', '2-2000', '4-3000', '5-0000', '5-0100', '5-1000', '4-4000', '3-1000'];
    private $paymentSubAccountCode = ['1-1000', '1-1100', '2-1000', '2-1020', '2-1050', '2-3000', '2-4000', '3-2000', '4-1000', '4-2000', '4-3000', '4-4000', '6-0000', '6-2000', '6-3000', '6-4000', '6-5000', '6-6000', '6-7000', '6-8000', '6-9000', '3-1000'];

    public function __construct(CashFlowService $cashFlowService, TrialBalanceService $trialBalanceService)
    {
        $this->cashFlowService = $cashFlowService;
        $this->trialBalanceService = $trialBalanceService;
    }

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

    public function CashFlowStatement($request)
    {
        $current = (isset($request->date) || $request->date != null) ? Carbon::parse($request->date) : Carbon::now();
        $previousMonth = $current->copy()->subMonth();

        $receiptSubAccountCode = ['2-1000', '2-1050', '2-2000', '4-3000', '5-0000', '5-0100', '5-1000', '4-4000', '3-1000'];
        $paymentSubAccountCode = ['1-1000', '1-1100', '2-1000', '2-1020', '2-1050', '2-3000', '2-4000', '3-2000', '4-1000', '4-2000', '4-3000', '4-4000', '6-0000', '6-2000', '6-3000', '6-4000', '6-5000', '6-6000', '6-7000', '6-8000', '6-9000', '3-1000'];

        $debitResults = $this->cashFlowService->getTransactionResults($receiptSubAccountCode, 'debit', $current);
        $creditResults = $this->cashFlowService->getTransactionResults($paymentSubAccountCode, 'credit', $current);
        $currentTotalDebitAmount = array_sum(array_column($debitResults, 'total_amount'));
        $currentTotalCreditAmount = array_sum(array_column($creditResults, 'total_amount'));

        $previousClosingBalance = $this->cashFlowService->getPreviousClosingBalance($previousMonth);
        $closingBalance = $this->cashFlowService->calculateClosingBalance($current, $previousClosingBalance);

        $cash_in_out_flow = $currentTotalDebitAmount - $currentTotalCreditAmount;

        return [
            'opening_balance' => $previousClosingBalance,
            'closing_balance' => $closingBalance,
            'total_receipt_amount' => $currentTotalDebitAmount,
            'total_payment_amount' => $currentTotalCreditAmount,
            'cash_in_out_flow' => $cash_in_out_flow,
            'cash_flow_statement' => array_merge($debitResults, $creditResults),
        ];
    }

    public function IndirectCashFlowStatement($request)
    {
        $receiptSubAccountCode = ['2-1050', '2-2000', '4-3000', '5-0000', '5-0100', '5-1000', '4-4000', '3-1000'];
        $paymentSubAccountCode = ['2-1020', '2-1050', '2-3000', '2-4000', '3-2000', '4-1000', '4-2000', '4-3000', '4-4000', '6-0000', '6-2000', '6-3000', '6-4000', '6-5000', '6-6000', '6-7000', '6-8000', '6-9000', '3-1000'];
        $purchaseOfFixedAsset = ['1-1000', '1-1100'];
        $current = (isset($request->date) || $request->date != null) ? Carbon::parse($request->date) : Carbon::now();
        $previousMonth = $current->copy()->subMonth();
        $cashInFlows = $this->cashFlowService->getIndirectCashFlowStatement($receiptSubAccountCode, 'debit', $current);
        $cashOutFlows = $this->cashFlowService->getIndirectCashFlowStatement($paymentSubAccountCode, 'credit_cash_out_flow', $current);
        $purchaseOfFixedAsset = $this->cashFlowService->getIndirectCashFlowStatement($purchaseOfFixedAsset, 'credit_fixed_asset', $current);
        $totalCashInFlow = array_sum(array_column($cashInFlows, 'amount'));
        $totalCashOutFlow = array_sum(array_column($cashOutFlows, 'amount'));

        $operating_activities = [
            'cash_in_flow' => $cashInFlows,
            'cash_out_flow' => $cashOutFlows,
            'total_cash_in_flow' => $totalCashInFlow,
            'total_cash_out_flow' => $totalCashOutFlow,
        ];
        $investing_activities = [
            'purchase_of_fixed_asset' => $purchaseOfFixedAsset,
        ];
        return [
            'operating_activities' => $operating_activities,
            'investing_activities' => $investing_activities,
        ];
    }

    public function BalanceSheet($request)
    {
        // $current = (isset($request->date) || $request->date != null) ? Carbon::parse($request->date) : Carbon::now();
        // $previousMonth = $current->copy()->subMonth();
        // $creditBalanceCode = [
        //     '5-0001',
        //     '5-0101',#food income
        //     '5-0002',
        //     '5-0102',#beverage income
        //     '5-0003',
        //     '5-0103', #room charges
        //     '5-1000', #income
        //     '3-1000',#capital
        //     '4-1000',#loan term liablilities
        // ];
        // $debitResults = $this->trialBalanceService->getTrialBalanceResults($creditBalanceCode, 'credit', $current,'credit_balance');
        // return $debitResults;
    }

    public function TrialBalance($request)
    {
        $current = (isset($request->date) || $request->date != null) ? Carbon::parse($request->date) : Carbon::now();
        $previousMonth = $current->copy()->subMonth();
        $incomeCode = [
            '5-0001',
            '5-0101',#food income
            '5-0002',
            '5-0102',#beverage income
            '5-0003',
            '5-0103', #room charges

            // '5-1000', #income

            // '3-1010',#existing capital
            // '3-1020',#retained earning
            // '4-1001',#loan
            // '4-1000',#loan term liablilities
        ];
        $otherIncomeCode = ['5-1000'];
        $capitalCode = ['3-1010'];
        $retainEarningCode = ['3-1020'];
        $longTermCode = ['4-1001'];

        $cashAndBankCode = ['2-1000'];
        $otherReceivableCode = ['2-1050'];

        #credit balance
        $income = $this->trialBalanceService->getTrialBalanceResults($incomeCode, 'credit', $current, 'credit_balance');
        // $otherIncome = $this->trialBalanceService->getTrialBalanceResults($otherIncomeCode, 'credit', $current,'credit_balance');
        $capital = $this->trialBalanceService->getTrialBalanceResults($capitalCode, 'credit', $current, 'credit_balance');
        $retainEarning = $this->trialBalanceService->getTrialBalanceResults($retainEarningCode, 'credit', $current, 'credit_balance');
        $longTerm = $this->trialBalanceService->getTrialBalanceResults($longTermCode, 'credit', $current, 'credit_balance');


        $creditBalance = [];
        $creditBalance[] = $income;
        $creditBalance[] = $capital;
        $creditBalance[] = $retainEarning;
        $creditBalance[] = $longTerm;
        $creditTotalBalance = 0;
        $creditTotalBalance += collect($income)->sum('amount');
        $creditTotalBalance += collect($capital)->sum('amount');
        $creditTotalBalance += collect($retainEarning)->sum('amount');
        $creditTotalBalance += collect($longTerm)->sum('amount');
        #end credit balance


        #debit balance


        $cashAndBankBalance = $this->trialBalanceService->getResultBySubAccountCode($cashAndBankCode, 'credit', $current, 'cash_and_bank');
        $otherReceiveable = $this->trialBalanceService->getTotalBySubAccountCode($otherReceivableCode, 'credit', $current, 'other_receiveable');
        //book value of current asset and fixed asset
        $fix_asset_tangiable = '1-1000';
        $fix_asset_untangible = '1-1100';
        $inventory_held = '2-1020';

        $fix_asset_tangiable = $this->trialBalanceService->getAssetBookValue($current, $fix_asset_tangiable, 'Fix Asset (Tangible)');
        $fix_asset_intangible = $this->trialBalanceService->getAssetBookValue($current, $fix_asset_untangible, 'Fix Asset (Intangible)');
        $inventory_held = $this->trialBalanceService->getAssetBookValue($current, $inventory_held, 'Schedule Of Inventory Held');
        $cashAndBankBalance = $cashAndBankBalance->merge($otherReceiveable);
        $cashAndBankBalance = $cashAndBankBalance->push($inventory_held);

        //cost & expense
        $costAndExpense = [
            '6-0000',
            '6-2000',
            '6-3000',
            '6-4000',
            '6-5000',
            '6-6000',
            '6-9000',
            '3-2000',
        ];
        $costAndExpense = $this->trialBalanceService->getTotalBySubAccountCode($costAndExpense, 'debit', $current, 'cost_and_expense');
        #end cost & expense

        #fixed overhead
        // $fixedOverHead=[];
        $financeCostCode = ['6-8000']; //finance cost
        $financeCost = $this->trialBalanceService->getTotalBySubAccountCode($financeCostCode, 'debit', $current, 'finance_cost');
        $fixExpenseCodes = [
            '6-7001', //rental 
            '6-7002', //depreciaion
            '6-7004', //replacement
        ];
        $fixExpense = $this->trialBalanceService->getTrialBalanceResults($fixExpenseCodes, 'debit', $current, 'fix_expense');

        $fixedOverHead = $financeCost->merge($fixExpense);
        #end fixed overhead

        $debitBalance = [];
        $debitBalance[] = [$fix_asset_tangiable];
        $debitBalance[] = [$fix_asset_intangible];
        $debitBalance[] = $cashAndBankBalance;
        $debitBalance[] = $costAndExpense;
        $debitBalance[] = $fixedOverHead;

        $debitTotalBalance = 0;
        $debitTotalBalance += collect(value: [$fix_asset_tangiable])->sum('amount');
        $debitTotalBalance += collect([$fix_asset_intangible])->sum('amount');
        $debitTotalBalance += collect($cashAndBankBalance)->sum('amount');
        $debitTotalBalance += collect($costAndExpense)->sum('amount');
        $debitTotalBalance += collect($fixedOverHead)->sum('amount');
        #end debit balance
        // $creditBalanceResult = new \stdClass();
        // $creditBalanceResult->credit_balance = $creditBalance;
        // $debitBalanceResult = new \stdClass();
        // $debitBalanceResult->debit_balance = $debitBalance;
        // $finalResults = [$creditBalanceResult, $debitBalanceResult];
        return [
            'credit_balance' => $creditBalance,
            'credit_total_balance' => $creditTotalBalance,
            'debit_total_balance' => $debitTotalBalance,
            'debit_balance' => $debitBalance
        ];
        // return $finalResults;
    }

}
