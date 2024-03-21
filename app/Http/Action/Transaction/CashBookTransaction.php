<?php

namespace App\Http\Action\Transaction;

use App\Models\Ledger;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;
use stdClass;

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

    public function getLatestClosedTransaction($data,$cashAccountId){
        $fromDate = convertDateFormat($data->from_date);
        return Transaction::with(['ledgers.account'])
        ->select(['id', 'date', 'description'])
        ->whereHas('ledgers', function ($query) use ($cashAccountId) {
            $query->where('account_id', $cashAccountId);    #transaction close depend on transaction
        })->where('is_closing', 1)
        ->orderByDesc('date')
        ->when(($data->from_date), function ($q) use ($fromDate) {
            $q->whereDate('date', '<', $fromDate);
        })
        ->first();
    }
}