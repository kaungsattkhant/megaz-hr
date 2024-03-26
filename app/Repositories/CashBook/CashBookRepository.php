<?php

namespace App\Repositories\CashBook;

use App\Http\Action\Transaction\CashBookTransaction;
use App\Models\Transaction;
use stdClass;

class CashBookRepository implements CashBookInterface
{
    public function list($request)
    {
        $cashAccountId = $request->cash_account_id;
        $latestClosedTransaction = (new CashBookTransaction())->getLatestClosedTransaction($request, $cashAccountId);
        $cashbookTransactions = Transaction::with(['ledgers.account'])
            ->isConfirmed(1)
            ->select(['id', 'date', 'description'])
            ->whereHas('ledgers', function ($query) use ($cashAccountId) {
                $query->where('account_id', $cashAccountId);
            })
            ->when(($request->from_date == null && $request->to_date == null) && $latestClosedTransaction, function ($q) use ($latestClosedTransaction) {
                $q->where('id', '>', $latestClosedTransaction->id);
            })
            ->get();
        $current_debit_amount = $current_credit_amount = 0;
        foreach ($cashbookTransactions as $transaction) {
            foreach ($transaction->ledgers as $ledger) {
                if ($ledger->account_id == $cashAccountId) {
                    $transaction->type = $ledger->account->name;
                    $transaction->amount = $ledger->value;
                    $transaction->action = $ledger->action;
                    $ledger->action == 'debit' ? $current_debit_amount += $transaction->amount : $current_credit_amount += $transaction->amount;
                } else {
                    $transaction->title = $ledger->account->name;
                }
            }
            UnsetData($transaction, ['ledgers']);
        }

        $balance = (new CashBookTransaction())->getOpeningBalance($request);
        $data = new stdClass();
        $data->opening_balance = $balance->opening_balance;
        $data->remaining_balance = ($balance->opening_balance + $current_debit_amount) - $current_credit_amount;
        $data->cashbook_list = $cashbookTransactions;
        return $data;
    }

    public function closeTransaction($request)
    {
        $cashAccountId = $request->cash_account_id;
        $latestTransaction = Transaction::
            whereHas('ledgers', function ($query) use ($cashAccountId) {
            $query->where('account_id', $cashAccountId); #transaction close depend on transaction
        })
            ->isConfirmed(1)
            ->latest()
            ->first();
        if ($latestTransaction) {
            $latestTransaction->is_closing = 1;
            $latestTransaction->closing_date = now();
            $latestTransaction->save();
            ResponseMessage('Transaction closing is successfully', 200);
        }
        ResponseMessage('Transaction closing is fail', 422);
    }
}
