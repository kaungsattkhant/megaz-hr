<?php

namespace App\Repositories\FinancialReport;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\CashbookBalance;
use App\Models\PurchaseOrderItem;
use App\Services\CashFlowService;
use Illuminate\Support\Facades\DB;
use App\Services\DepreciationService;
use App\Services\TrialBalanceService;
use App\Services\InventoryFinancialService;

class FinancialRepository implements FinancialInterface
{
    protected $cashFlowService;
    protected $trialBalanceService;

    protected $depreciationService;

    protected $inventoryFinancialService;
    private $receiptSubAccountCode = ['2-1000', '2-1050', '2-2000', '4-3000', '5-0000', '5-0100', '5-1000', '4-4000', '3-1000'];
    private $paymentSubAccountCode = ['1-1000', '1-1100', '2-1000', '2-1020', '2-1050', '2-3000', '2-4000', '3-2000', '4-1000', '4-2000', '4-3000', '4-4000', '6-0000', '6-2000', '6-3000', '6-4000', '6-5000', '6-6000', '6-7000', '6-8000', '6-9000', '3-1000'];

    public function __construct(CashFlowService $cashFlowService, TrialBalanceService $trialBalanceService, InventoryFinancialService $inventoryFinancialService, DepreciationService $depreciationService)
    {
        $this->cashFlowService = $cashFlowService;
        $this->trialBalanceService = $trialBalanceService;
        $this->inventoryFinancialService = $inventoryFinancialService;
        $this->depreciationService = $depreciationService;

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
        $currentLiabilitieCode = ['4-2000', '4-3000', '4-4000'];

        // how to retrieve accrued

        #credit balance
        $income = $this->trialBalanceService->getTrialBalanceResults($incomeCode, 'credit', $current, 'credit_balance');
        // $otherIncome = $this->trialBalanceService->getTrialBalanceResults($otherIncomeCode, 'credit', $current,'credit_balance');
        $capital = $this->trialBalanceService->getTrialBalanceResults($capitalCode, 'credit', $current, 'credit_balance');
        $retainEarning = $this->trialBalanceService->getTrialBalanceResults($retainEarningCode, 'credit', $current, 'credit_balance');
        $longTerm = $this->trialBalanceService->getTrialBalanceResults($longTermCode, 'credit', $current, 'credit_balance');

        $longTerm = $this->trialBalanceService->getTrialBalanceResults($longTermCode, 'credit', $current, 'credit_balance');

        $currentLiabilities = $this->trialBalanceService->getTotalBySubAccountCode($currentLiabilitieCode, 'credit', $current, 'creditor_balance');

        $creditBalance = [];
        $creditBalance[] = $income;
        $creditBalance[] = $capital;
        $creditBalance[] = $retainEarning;
        $creditBalance[] = $longTerm;
        $creditBalance[] = $currentLiabilities;
        $creditTotalBalance = 0;
        $creditTotalBalance += collect($income)->sum('amount');
        $creditTotalBalance += collect($capital)->sum('amount');
        $creditTotalBalance += collect($retainEarning)->sum('amount');
        $creditTotalBalance += collect($longTerm)->sum('amount');
        $creditTotalBalance += collect($currentLiabilities)->sum('amount');
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

    public function getProfitAndLoss($request)
    {
        $current = (isset($request->date) || $request->date != null) ? Carbon::parse($request->date) : Carbon::now();
        $currentMonth = $current->month;
        $cashSaleCode = ['5-0000', '5-0100'];
        $rt_sale = ['5-0001', '5-0002'];
        $ktv_sale = ['5-0101', '5-0102'];

        $incomeCode = ['5-1000'];

        //overhead code
        $overheadCode = [
            '6-3000', //operating expense
            '6-4000', //admin & general 
            '6-5000' // gov affairs
        ];

        //fix cost
        $rentalAccountCode = ['6-7001'];
        //finaance cost code
        $financeCostCode = ['6-8000'];
        //year tax cost
        $yearTaxCode = ['6-9000'];
        // //total consumption for food and beverage 
        // $name=['Inventory Food','Inventory Beverage'];
        // $catetoryIds=Category::whereIn('name',$name)->pluck('id')->toArray();

        // //total consumption 
        // $totalConsumption= $this->inventoryFinancialService->inventoryScheduleTotalWithTotalByMonth($catetoryIds,$currentMonth);

        // $rtTotalSaleByAccount = $this->trialBalanceService->getTrialBalanceResults($rt_sale, 'credit', $current, 'sale');
        // $ktvTotalSaleByAccount = $this->trialBalanceService->getTrialBalanceResults($ktv_sale, 'credit', $current, 'sale');
        // $rtTotalSale = $this->getSum($rtTotalSaleByAccount);
        // $ktvTotalSale = $this->getSum($ktvTotalSaleByAccount);
        // $totalRevenue=$ktvTotalSale+$rtTotalSale;   //total sale revenue

        // $ratio=($totalConsumption->total_consumption/$totalRevenue)*100;
        // $rt_cos=($rtTotalSale*$ratio)/100;
        // $ktv_cos=($ktvTotalSale*$ratio)/100;

        // $cashSale = $this->trialBalanceService->getTotalBySubAccountCode($cashSaleCode, 'credit', $current, 'cash_sale');
        // $rtCashSale=0;
        // $ktvCashSale=0;
        // foreach ($cashSale as $sale) {
        //     if($sale->code=='5-0000'){
        //         $rtCashSale += $sale->total_amount;
        //     }
        //     if($sale->code=='5-0100'){
        //         $ktvCashSale += $sale->total_amount;
        //     }
        // }
        // $rtGP=$rtCashSale-$rt_cos;
        // $ktvGp=$ktvCashSale-$ktv_cos;
        // return [$rtGP,$ktvGp];
        // Total consumption for food and beverage 
        $categoryNames = ['Inventory Food', 'Inventory Beverage'];
        $categoryIds = Category::whereIn('name', $categoryNames)->pluck('id')->toArray();

        // Total consumption 
        $totalConsumption = $this->inventoryFinancialService->inventoryScheduleTotalWithTotalByMonth($categoryIds, $currentMonth);

        // Total sale revenue for RT and KTV
        $rtTotalSale = $this->getSum($this->trialBalanceService->getTrialBalanceResults($rt_sale, 'credit', $current, 'sale'));
        $ktvTotalSale = $this->getSum($this->trialBalanceService->getTrialBalanceResults($ktv_sale, 'credit', $current, 'sale'));
        $totalRevenue = $rtTotalSale + $ktvTotalSale;

        $rt_cos = $ktv_cos = 0;
        if ($totalRevenue > 0) {
            $ratio = ($totalConsumption->total_consumption / $totalRevenue) * 100;
            $rt_cos = ($rtTotalSale * $ratio) / 100;
            $ktv_cos = ($ktvTotalSale * $ratio) / 100;
        }

        // $rt_expense = (22850612.93 * 9.71898444) / 100;
        // return $rt_expense;
        // Cash Sale Processing

        $cashSales = $this->trialBalanceService->getTotalBySubAccountCode($cashSaleCode, 'credit', $current, 'cash_sale');

        $rtCashSale = $cashSales->where('code', '5-0000')->sum('total_amount');
        $ktvCashSale = $cashSales->where('code', '5-0100')->sum('total_amount');
        $rtGP = $rtCashSale - $rt_cos;
        $ktvGP = $ktvCashSale - $ktv_cos;

        //other income
        $otherIncome = $this->trialBalanceService->getTotalResultBySubAccountCode($incomeCode, 'credit', $current, 'cash_sale');
        $otherIncomeTotalAmount = $otherIncome[0]->total_amount;
        $otherIncomePL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $otherIncomeTotalAmount, $totalRevenue);

        $total_rt_operating_income = $rtGP + $otherIncomePL[0];
        $total_ktv_operating_income = $ktvGP + $otherIncomePL[1];
        $total_operating_income = $total_rt_operating_income + $total_ktv_operating_income;
        // return $total_operating_income;
        //end operating income 

        //overhead
        // $overheadCode = [
        //     '6-3000', //operating expense
        //     '6-4000', //admin & general 
        //     '6-5000' // gov affairs
        // ];

        $operating_expense_code = ['6-4000'];
        $adminstrative_code = ['6-4000'];
        $pay_and_related_code = ['6-5000'];

        $operatingExpense = $this->trialBalanceService->getTotalBySubAccountCode($operating_expense_code, 'debit', $current, 'operating_expense');
        $operatingExpensePL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $operatingExpense[0]->total_amount, $totalRevenue);

        // adminstrative cost
        $adminstrativeCost = $this->trialBalanceService->getTotalBySubAccountCode($adminstrative_code, 'debit', $current, 'adminstrative_cost');
        $adminstrativeCostPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $adminstrativeCost[0]->total_amount, $totalRevenue);
        //pay and related cost
        $payAndRelatedCost = $this->trialBalanceService->getTotalBySubAccountCode($pay_and_related_code, 'debit', $current, 'pay_and_related');
        $payAndRelatedCostPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $payAndRelatedCost[0]->total_amount, $totalRevenue);

        $total_rt_over_head = $operatingExpensePL[0] + $adminstrativeCostPL[0] + $operatingExpensePL[0];
        $total_ktv_over_head = $operatingExpensePL[1] + $adminstrativeCostPL[1] + $operatingExpensePL[1];
        $total_over_head = $total_rt_over_head + $total_ktv_over_head;

        //end overhead

        //fix cost
        $rentalCost = $this->trialBalanceService->getTrialBalanceResults($rentalAccountCode, 'debit', $current, 'cash_sale');
        $rentalPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $rentalCost[0]->amount, $totalRevenue);

        $fix_asset_tangiable = '1-1000';
        $fix_asset_untangible = '1-1100';
        $inventory_held = '2-1020';
        $month = Carbon::parse($request->date)->format('n');
        $year = Carbon::parse($request->date)->format('Y');
        $fix_asset_tangiable = $this->depreciationService->depreciationBalanceQuery($fix_asset_tangiable, $month, $year);
        $fix_asset_untangible = $this->depreciationService->depreciationBalanceQuery($fix_asset_untangible, $month, $year);
        $total_fix_asset_tangiable = $fix_asset_tangiable->sum('current_month_depreciation');
        $total_fix_asset_untangiable = $fix_asset_untangible->sum('current_month_depreciation');
        $total_fix_asset = $total_fix_asset_tangiable + $total_fix_asset_untangiable;
        $fixAssetPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $total_fix_asset, $totalRevenue);

        $current_asset = $this->depreciationService->depreciationBalanceQuery($inventory_held, $month, $year);
        $total_current_asset = $current_asset->sum('current_month_depreciation');
        $currentAssetPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $total_current_asset, $totalRevenue);

        $total_rt_fix_cost = $rentalPL[0] + $fixAssetPL[0] + $currentAssetPL[0];
        $total_ktv_fix_cost = $rentalPL[1] + $fixAssetPL[1] + $currentAssetPL[1];
        $total_fix_cost = $total_rt_fix_cost + $total_ktv_fix_cost;


        $net_profit=$total_operating_income-($total_over_head+$total_fix_cost);
        //finance cost
        $financeCost = $this->trialBalanceService->getTotalResultBySubAccountCode($financeCostCode, 'debit', $current, 'cash_sale');
        $financeCostPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $financeCost[0]->total_amount, $totalRevenue);

        //year teax
        $yearText = $this->trialBalanceService->getTotalResultBySubAccountCode($yearTaxCode, 'debit', $current, 'cash_sale');
        $yearTaxPL = $this->calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $yearText[0]->total_amount, $totalRevenue);
        $total_rt_interest_and_tax=$financeCostPL[0]+$yearTaxPL[0];
        $total_ktv_interest_and_tax=$financeCostPL[1]+$yearTaxPL[1];
        $total_interest_and_tax=$total_rt_interest_and_tax+$total_ktv_interest_and_tax;

        $rt_net_profit=$total_rt_operating_income-($total_rt_over_head+$total_rt_fix_cost);
        $ktv_net_profit=$total_ktv_operating_income-($total_ktv_over_head+$total_ktv_fix_cost);
        $total_net_profit=$rt_net_profit+$ktv_net_profit;



        $rt_net_profit_after_tax=$rt_net_profit-$total_rt_interest_and_tax;
        $ktv_net_profit_after_tax=$ktv_net_profit-$total_ktv_interest_and_tax;
        $total_net_profit_after_tax=$rt_net_profit_after_tax+$ktv_net_profit_after_tax;

        $response=[];

        $operating_income = [];
        $cashSaleResponse = [
            "name" => "Gross Profit",  // Fixed name
            "restaurant_pl" => $rtGP,      // Default values
            "ktv_pl" => $ktvGP,
            "total" => $rtGP + $ktvGP,
        ];
        $operating_income[] = $cashSaleResponse;
        $operating_income[]=$this->setResponseFormat('Other Income ',$otherIncomePL);
        $operating_income_response=$this->setTotalResponseFormat('Total Operating Income',$total_rt_operating_income,$total_ktv_operating_income,$total_operating_income);
    
        // $otherIncomeResponse = [
        //     "name" => "Other Income ",  // Fixed name
        //     "restaurant_pl" => $otherIncomePL[0],      // Default values
        //     "ktv_pl" => $otherIncomePL[1],
        //     "total" => $otherIncomePL[0] + $otherIncomePL[1],
        // ];
        // $operating_income[] = $otherIncomeResponse;
        // return $total_operating_income;

        $overhead=[];
        $overhead[]=$this->setResponseFormat('Operating Expense',$operatingExpensePL);
        $overhead[]=$this->setResponseFormat('Adminstrative Cost',$adminstrativeCostPL);
        $overhead[]=$this->setResponseFormat('Pay & Related Expenses',$payAndRelatedCostPL);
        $total_overhead_response=$this->setTotalResponseFormat('Total Operating Income',$total_rt_over_head,$total_ktv_over_head,$total_over_head);
        // return $operatingExpenseResponse;

        $fixedCost[]=$this->setResponseFormat('Rental Fee',$rentalPL);
        $fixedCost[]=$this->setResponseFormat('CA-Deprciation',$currentAssetPL);
        $fixedCost[]=$this->setResponseFormat('FA-Depreciation',$fixAssetPL);
        $total_fixed_cost_response=$this->setTotalResponseFormat('Total Fixed Cost',$total_rt_fix_cost,$total_ktv_fix_cost,$total_fix_cost);

        // $net_profit_before=$this->setTotalResponseFormat('Net Profit (Earning Before Interest & Taxes)',,$total_ktv_interest_and_tax,$total_interest_and_tax);

        $interestAndTax[]=$this->setResponseFormat('Finance Cost',$financeCostPL);
        $interestAndTax[]=$this->setResponseFormat('Year Tax',$yearTaxPL);
        $total_interest_and_tax_response=$this->setTotalResponseFormat('Total Interest And Tax',$total_rt_interest_and_tax,$total_ktv_interest_and_tax,$total_interest_and_tax);
        $net_profit=$this->setTotalResponseFormat('Net Profit (Earning Before Interest & Taxes)',$rt_net_profit,$ktv_net_profit,$total_net_profit);
        $net_profit_after_tax=$this->setTotalResponseFormat('Net Profit After Inerest &Tax',$rt_net_profit_after_tax,$ktv_net_profit_after_tax,$total_net_profit_after_tax);

        $responseOperating['data']=$operating_income;
        $responseOperating['total']=$operating_income_response;

        $responseOverhead['data']=$overhead;
        $responseOverhead['total']=$total_overhead_response;

        $responseFixedCost['data']=$fixedCost;
        $responseFixedCost['total']=$total_fixed_cost_response;

        $responseNetProfit['total']=$net_profit;

        $responseInterestAndTax['data']=$interestAndTax;
        $responseInterestAndTax['total']=$total_interest_and_tax_response;

        $responseNetProfitAfterTax['total']=$net_profit_after_tax;

        $data[]=$responseOperating;
        $data[]=$responseOverhead;
        $data[]=$responseFixedCost;
        $data[]=$responseNetProfit;
        $data[]=$responseInterestAndTax;
        $data[]=$responseNetProfitAfterTax;
        return $data;
    }
    public function setResponseFormat($name,$plData){
        return  [
            "name" => $name,  // Fixed name
            "restaurant_pl" => $plData[0],      // Default values
            "ktv_pl" => $plData[1],
            "total" => $plData[0] + $plData[1],
        ];
    }
    public function setTotalResponseFormat($name,$total_rt,$total_ktv,$total){
        return  [
            "name" => $name,  // Fixed name
            "total_restaurant_pl" => $total_rt,      // Default values
            "total_ktv_pl" => $total_ktv,
            "total" => $total,
        ];
    }
    public function calRTAndKTVPL($rtTotalSale, $ktvTotalSale, $total, $totalRevenue)
    {
        $rt_pl = $ktv_pl = 0;
        if ($totalRevenue > 0) {
            $ratio = ($total / $totalRevenue) * 100;
            $rt_pl = ($rtTotalSale * $ratio) / 100;
            $ktv_pl = ($ktvTotalSale * $ratio) / 100;
        }
        return [$rt_pl, $ktv_pl];
    }

    public function getInventorySchedule($request)
    {
        // $current = (isset($request->date) || $request->date != null) ? Carbon::parse($request->date) : Carbon::now();
        $currentMonth = Carbon::now()->month;

        // return $this->inventoryFinancialService->inventoryScheduleWithTypeByMonth($currentMonth);
        return $this->inventoryFinancialService->inventoryScheduleWithCategoryByMonth($currentMonth);
        // $purchaseOrder = PurchaseOrderItem::
        //     join('po_grns', 'purchase_order_items.id', '=', 'po_grns.purchase_order_item_id')
        //     ->join('purchase_orders', 'purchase_order_items.purchase_order_id', 'purchase_orders.id')
        //     ->join('items', 'purchase_order_items.item_id', 'items.id')
        //     ->join('categories', 'items.category_id', 'categories.id')
        //     ->join('item_types', 'items.item_type_id', 'item_types.id')
        //     ->select(
        //         'categories.id as category_id',
        //         'item_types.id as item_type_id',
        //         'categories.name as category_name',
        //         'item_types.name as item_type_name',
        //         DB::raw('SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount'),
        //         DB::raw('SUM(po_grns.invoice_amount) as cash_purchase_amount'),
        //         DB::raw('SUM(purchase_order_items.quantity * purchase_order_items.amount) - SUM(po_grns.invoice_amount) as credit_purchase_amount')
        //     )
        //     ->where('purchase_orders.is_bought', 1)
        //     ->whereMonth('purchase_orders.purchased_date_time', $current)
        //     ->groupBy('items.category_id', 'items.item_type_id','categories.id', 'item_types.id')
        //     ->get();

        // $itemConsumption = DB::table('order_items')
        //     ->join('menus', 'order_items.menu_id', '=', 'menus.id')
        //     ->join('item_menu', 'order_items.menu_id', '=', 'item_menu.menu_id')
        //     ->join('items', 'item_menu.item_id', '=', 'items.id')
        //     ->join('categories', 'items.category_id', '=', 'categories.id')
        //     ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
        //     ->select(
        //         'categories.id as category_id',
        //         'item_types.id as item_type_id',
        //         'categories.name as category_name',
        //         'item_types.name as item_type_name',
        //         DB::raw('SUM((item_menu.price * item_menu.weight) * order_items.quantity) as consumption'),
        //         'items.name as item',
        //         'item_menu.uom_id as uom'
        //     )
        //     ->groupBy('categories.name', 'item_types.name', 'items.name', 'item_menu.uom_id', 'categories.id', 'item_types.id')  // Group by category, item type, and optionally item and uom.
        //     ->get();
        // return $itemConsumption;

        // $itemSummary = DB::table('items')
        //     ->join('categories', 'items.category_id', '=', 'categories.id')
        //     ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
        //     // Subquery for Purchase Order Data
        //     ->leftJoin(DB::raw('(SELECT 
        //                 items.category_id,
        //                 items.item_type_id,
        //                 SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount,
        //                 SUM(po_grns.invoice_amount) as cash_purchase_amount,
        //                 SUM(purchase_order_items.quantity * purchase_order_items.amount) - SUM(po_grns.invoice_amount) as credit_purchase_amount
        //             FROM purchase_order_items
        //             JOIN po_grns ON purchase_order_items.id = po_grns.purchase_order_item_id
        //             JOIN purchase_orders ON purchase_order_items.purchase_order_id = purchase_orders.id
        //             JOIN items ON purchase_order_items.item_id = items.id
        //             WHERE purchase_orders.is_bought = 1
        //             AND MONTH(purchase_orders.purchased_date_time) = ' . $currentMonth . '
        //             GROUP BY items.category_id, items.item_type_id) as purchase_orders_summary'), function ($join) {
        //         $join->on('items.category_id', '=', 'purchase_orders_summary.category_id')
        //             ->on('items.item_type_id', '=', 'purchase_orders_summary.item_type_id');
        //     })
        //     // Subquery for Item Consumption Datax  
        //     ->leftJoin(DB::raw('(SELECT 
        //                 items.category_id,
        //                 items.item_type_id,
        //                 SUM((item_menu.price * item_menu.weight) * order_items.quantity) as consumption
        //             FROM order_items
        //             JOIN item_menu ON order_items.menu_id = item_menu.menu_id
        //             JOIN items ON item_menu.item_id = items.id
        //             WHERE order_items.status = "sold"
        //             GROUP BY items.category_id, items.item_type_id) as item_consumption_summary'), function ($join) {
        //         $join->on('items.category_id', '=', 'item_consumption_summary.category_id')
        //             ->on('items.item_type_id', '=', 'item_consumption_summary.item_type_id');
        //     })
        //     // Select Required Fields
        //     ->select(
        //         'categories.name as category_name',
        //         'item_types.name as item_type_name',
        //         DB::raw('COALESCE(purchase_orders_summary.total_amount, 0) as total_amount'),
        //         DB::raw('COALESCE(purchase_orders_summary.cash_purchase_amount, 0) as cash_purchase_amount'),
        //         DB::raw('COALESCE(purchase_orders_summary.credit_purchase_amount, 0) as credit_purchase_amount'),
        //         DB::raw('COALESCE(item_consumption_summary.consumption, 0) as consumption'),
        //         DB::raw('(COALESCE(purchase_orders_summary.total_amount, 0) - COALESCE(item_consumption_summary.consumption, 0)) as closing_balance') // Closing balance calculation
        //     )
        //     ->groupBy('categories.name', 'item_types.name', 'items.category_id', 'items.item_type_id')
        //     ->get();
        // return $itemSummary;

        // $previousMonth = $currentMonth - 1 ?: 12; // Handles the case when the current month is January

        //groupBy category_id and item type
    }

    protected function getSum($data)
    {
        $sum = 0;
        foreach ($data as $d) {
            $sum += $d->amount;
        }
        return $sum;
    }

}
