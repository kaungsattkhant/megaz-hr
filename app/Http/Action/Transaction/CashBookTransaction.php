<?php

namespace App\Http\Action\Transaction;

use stdClass;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class CashBookTransaction
{
    public function getOpeningBalance($data){
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
            $query->whereIn('account_id', $cashAccountId);    #transaction close depend on transaction
        })->where('is_closing', 1)
        ->orderByDesc('date')
        ->isConfirmed(1)
        ->when(($data->from_date), function ($q) use ($fromDate) {
            $q->whereDate('date', '<', $fromDate);
        })
        ->first();
    }
}