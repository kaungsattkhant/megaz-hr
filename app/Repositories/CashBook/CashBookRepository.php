<?php

namespace App\Repositories\CashBook;

use App\Http\Action\Transaction\CashBookTransaction;
use App\Models\Transaction;

class CashBookRepository implements CashBookInterface
{
    public function list($request)
    {
        // $cashAccountIds = range(23, 32);
        $cashAccountId=$request->cash_account_id;
        $latestClosedTransaction = (new CashBookTransaction())->getLatestClosedTransaction($cashAccountId);
        $cashbookTransactions = Transaction::with(['ledgers.account'])
            ->select(['id', 'date', 'description'])
            ->whereHas('ledgers', function ($query) use ($cashAccountId) {
                // $query->whereIn('account_id', $cashAccountIds);
                $query->where('account_id', $cashAccountId);

            })
            ->when($latestClosedTransaction, function ($q) use ($latestClosedTransaction) {
                $q->where('id', '>', $latestClosedTransaction);
            })
            ->get();
        foreach ($cashbookTransactions as $transaction) {
            foreach ($transaction->ledgers as $ledger) {
                // if (in_array($ledger->account_id, $cashAccountIds)) {
                if ($ledger->account_id==$cashAccountId) {
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

    public function closeTransaction($request){
        $cashAccountId=$request->cash_account_id;
        $latestTransaction=Transaction::
        whereHas('ledgers', function ($query) use ($cashAccountId) {
            $query->where('account_id', $cashAccountId);    #transaction close depend on transaction
        })
        ->latest()
        ->first();
        if($latestTransaction){
            $latestTransaction->is_closing=1;
            $latestTransaction->closing_date=now();
            $latestTransaction->save();
            ResponseMessage('Transaction closing is successfully',200);
        }
        ResponseMessage('Transaction closing is fail',422);
    }
}
