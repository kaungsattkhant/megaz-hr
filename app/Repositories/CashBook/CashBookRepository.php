<?php

namespace App\Repositories\CashBook;

use App\Http\Action\Transaction\CashBookTransaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CashBookRepository implements CashBookInterface
{
    public function list($request)
    {
        // $cashBooks = DB::table('ledgers')
        //     ->whereIn('ledgers.account_id', $cashAccountIds)
        //     ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
        //     ->join('accounts', 'ledgers.account_id', '=', 'accounts.id')
        //     ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
        //     ->select(
        //         'transactions.description',
        //         'accounts.name as account_name',
        //         'ledgers.value',
        //         'sub_accounts.name as sub_account_name',
        //         // DB::raw('SUM(ledgers.value) as balance')
        //     )
        //     ->get();
        $cashAccountIds = range(23, 32);
        $cashbookTransactions = Transaction::with(['ledgers.account'])
            ->select(['id', 'date', 'description'])
            ->whereHas('ledgers', function ($query) use ($cashAccountIds) {
                $query->whereIn('account_id', $cashAccountIds);
            })->get();
            
        foreach ($cashbookTransactions as $transaction) {
            foreach ($transaction->ledgers as $ledger) {
                if (in_array($ledger->account_id, $cashAccountIds)) {
                    $transaction->type = $ledger->account->name;
                    $transaction->amount = $ledger->value;
                } else {
                    $transaction->title = $ledger->account->name;
                }
            }
            UnsetData($transaction, ['ledgers']);
        }
        // $cashbookTransactions = Transaction::leftJoin('ledgers', 'transactions.id', '=', 'ledgers.transaction_id')
        // ->leftJoin('accounts', 'ledgers.account_id', '=', 'accounts.id')
        // ->select('transactions.id', 'transactions.date', 'transactions.description',
        //          'accounts.name as account_name', 'ledgers.value as ledger_value', 'ledgers.account_id as ledger_account_id')
        // ->whereIn('ledgers.account_id', $cashAccountIds)
        // ->get();
        // $cashbookTransactions->each(function ($transaction) use ($cashAccountIds) {
        //     if (in_array($transaction->ledger_account_id, $cashAccountIds)) {
        //         $transaction->type = $transaction->account_name;
        //         $transaction->amount = $transaction->ledger_value;
        //     } else {
        //         $transaction->title = $transaction->account_name;
        //     }
        //     unset($transaction->account_name, $transaction->ledger_value, $transaction->ledger_account_id);
        // });

        return $cashbookTransactions;
    }
}
