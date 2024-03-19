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
    public function getOpeningBalance(){
        $cashAccountIds = range(23, 32);
        $latestClosedTransaction =$this->getLatestClosedTransaction();
        if($latestClosedTransaction){
            $balance = DB::table('ledgers')
            ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'ledgers.account_id', '=', 'accounts.id')
            ->whereIn('ledgers.account_id', $cashAccountIds)
            ->where('transactions.id', '<=', $latestClosedTransaction)
            // ->whereDate('transactions.date', '<=', $closingDate) // Compare transaction date with closing date
            ->select(
                  DB::raw('SUM(CASE WHEN ledgers.action = "debit" AND DATE(transactions.date) THEN ledgers.value ELSE 0 END) as debit_balance'),
                DB::raw('SUM(CASE WHEN ledgers.action = "credit" AND DATE(transactions.date) THEN ledgers.value ELSE 0 END) as credit_balance'),
                // DB::raw('SUM(CASE WHEN ledgers.action = "debit" AND DATE(transactions.date) = CURDATE() THEN ledgers.value ELSE 0 END) as current_debit_balance'),
                // DB::raw('SUM(CASE WHEN ledgers.action = "credit" AND DATE(transactions.date) = CURDATE() THEN ledgers.value ELSE 0 END) as current_credit_balance'),
                DB::raw('(SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) -
              SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END)) as opening_balance'))
            ->first();
            return $balance;
        }
        $balance=new stdClass();
        $balance->opening_balance=0;
        return $balance;
        
        // $closingBalance = $balance->opening_balance + $balance->current_debit_balance - $balance->current_credit_balance;
        // $balance->closing_balance=$closingBalance;
        // return $balance;

      
    }

    public function getLatestClosedTransaction(){
        return  DB::table('transactions')
        ->where('is_closing', true)
        ->orderByDesc('date')
        ->value('id');
    }
}