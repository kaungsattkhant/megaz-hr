<?php

namespace App\Http\Action\Transaction;

use stdClass;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use App\Models\CashbookBalance;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class CashBookTransaction
{
    public function getOpeningBalanceOriginal($data){
        $cashAccountId=$data->cash_account_id;
        $latestClosedTransaction =$this->getLatestClosedTransaction($data,$cashAccountId);
        if($latestClosedTransaction){
            $balance = DB::table('ledgers')
            ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'ledgers.account_id', '=', 'accounts.id')
            ->where('transactions.is_confirmed',1)
            ->where('ledgers.account_id', $cashAccountId)
            ->where('transactions.id', '<=', $latestClosedTransaction->id)
            // ->whereDate('transactions.date', '<=', $transaction_date_filter)
            // ->whereDate('transactions.date', '<=', $closingDate) // Compare transaction date with closing date
            ->select(
                  DB::raw('SUM(CASE WHEN ledgers.action = "debit" AND DATE(transactions.date) THEN ledgers.value ELSE 0 END) as debit_balance'),
                DB::raw('SUM(CASE WHEN ledgers.action = "credit" AND DATE(transactions.date) THEN ledgers.value ELSE 0 END) as credit_balance'),
                DB::raw('(SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) -
              SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END)) as opening_balance'))
            ->first();
            if(is_null($balance->opening_balance)){
                $balance=new stdClass();
                $balance->opening_balance=0;
                return $balance;
            }
            return $balance;
        }
        $balance=new stdClass();
        $balance->opening_balance=0;
        return $balance;
    }

    public function getOpeningBalance($data){
        $cashAccountId=$data->cash_account_id;
        $month=Carbon::now()->subMonth();
        // $latestClosedTransaction =$this->getLatestClosedTransaction($data,$cashAccountId);
        $balance= CashbookBalance::where('year', $month->year)
        ->where('month', $month->month)
        ->where('cash_account_id',$cashAccountId)
        ->value('closing_balance') ?? 0;
        $openingBalance=new stdClass;
        $openingBalance->opening_balance=$balance;
        return $openingBalance;
    }

    public function getClosingBalance($openingBalance,$data){

        $cash_account_id=$data->cash_account_id;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $totals = DB::table('ledgers')
            ->select(
                DB::raw('SUM(CASE WHEN action = "debit" THEN value ELSE 0 END) as total_debit_amount'),
                DB::raw('SUM(CASE WHEN action = "credit" THEN value ELSE 0 END) as total_credit_amount')
            )
            ->where('account_id', $cash_account_id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->first();
        $totalDebitAmount = (int)$totals->total_debit_amount;
        $totalCreditAmount = (int)$totals->total_credit_amount;
        $closingBalance=((int)$openingBalance+$totalDebitAmount)-$totalCreditAmount;
        return $closingBalance;
    }

    public function getLatestClosedTransaction($data,$cashAccountId){
        $fromDate = convertDateFormat($data->from_date);
        return Transaction::with(['ledgers.account'])
        ->withoutGlobalScope('dateFilter')
        ->select(['id', 'date', 'description'])
        ->whereHas('ledgers', function ($query) use ($cashAccountId) {
            $query->where('account_id', $cashAccountId);    #transaction close depend on transaction
        })->where('is_closing', 1)
        ->orderByDesc('date')
        ->isConfirmed(1)
        ->when(($data->from_date), function ($q) use ($fromDate) {
            $q->whereDate('date', '<', $fromDate);
        })
        ->first();
    }

    public function getCashAndBankBalanceByMonth($sub_account_id,$year,$month){
      
        // return DB::table('sub_accounts')
        // ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
        // ->leftJoin('ledgers', function ($join) use ($year) {
        //     $join->on('ledgers.account_id', '=', 'accounts.id')
        //         ->whereYear('ledgers.created_at', $year); // Only filter by year
        // })
        // ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
        // ->whereIn('sub_accounts.account_code', $sub_account_id)
        // ->selectRaw('
        //     MONTH(ledgers.created_at) as month,
        //     DATE_FORMAT(ledgers.created_at, "%Y-%m-01") as date,
        //     SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as total_debit,
        //     SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as total_credit
        // ')
        // ->groupBy(DB::raw('MONTH(ledgers.created_at), DATE_FORMAT(ledgers.created_at, "%Y-%m-01")'))
        // ->orderBy(DB::raw('MONTH(ledgers.created_at)'))
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
        
        // Step 2: Query to get debit and credit sums by month for the specified year
        $ledgerData = DB::table('sub_accounts')
            ->leftJoin('accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->leftJoin('ledgers', function ($join) use ($year) {
                $join->on('ledgers.account_id', '=', 'accounts.id')
                    ->whereYear('ledgers.created_at', $year);
            })
            ->leftJoin('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->whereIn('sub_accounts.account_code', $sub_account_id)
            ->selectRaw('
                MONTH(ledgers.created_at) as month,
                SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as total_debit,
                SUM(CASE WHEN transactions.is_confirmed = 1 AND ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as total_credit
            ')
            ->groupBy(DB::raw('MONTH(ledgers.created_at)'))
            ->get();
        
        // Step 3: Merge results with months of the year, calculating value as total_debit - total_credit
        $results = $monthsOfYear->map(function ($month) use ($ledgerData) {
            $data = $ledgerData->firstWhere('month', $month['month_number']);
        
            return [
                'month' => $month['month_name'],
                // 'date' => $month['date'],
                'value' => $data ? ($data->total_debit - $data->total_credit) : 0,
            ];
        });
        
        // Step 4: Output the results
        return $results;
    }
}