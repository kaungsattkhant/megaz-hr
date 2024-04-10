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
        $cashbookTransactions = Transaction::with(['ledgers.account', 'transactionable'])
            ->isConfirmed(1)
            ->select(['id', 'date', 'description', 'transactionable_id', 'transactionable_type'])
            ->whereHas('ledgers', function ($query) use ($cashAccountId) {
                $query->whereIn('account_id', $cashAccountId);
            })
            ->when(($request->from_date == null && $request->to_date == null) && $latestClosedTransaction, function ($q) use ($latestClosedTransaction) {
                $q->where('id', '>', $latestClosedTransaction->id);
            })
            ->get();
        $current_debit_amount = $current_credit_amount = 0;
        foreach ($cashbookTransactions as $transaction) {
            if ($transaction->transactionable_type == 'invoice') {
                // dd($transaction->transactionable);
            }
            foreach ($transaction->ledgers as $ledger) {
                if (in_array(config('common.pos_cash'), $cashAccountId) || in_array(config('common.pos_cash'), $cashAccountId)) {
                    $ledger->action == 'debit' ? $current_debit_amount += $transaction->amount : $current_credit_amount += $transaction->amount;
                    $transaction->title = $transaction->transactionable_type == 'invoice' ? $ledger->account->name . '(' . $transaction->transactionable->invoice_id . ')' : $ledger->account->name;
                    $transaction->type = $ledger->account->name;
                    $transaction->amount = $ledger->value;
                    $transaction->action = $ledger->action;
                    // $transaction->title = $ledger->account->name;
                } else {
                    if (in_array($ledger->account_id, $cashAccountId)) {
                        $transaction->type = $ledger->account->name;
                        $transaction->amount = $ledger->value;
                        $transaction->action = $ledger->action;
                        $ledger->action == 'debit' ? $current_debit_amount += $transaction->amount : $current_credit_amount += $transaction->amount;
                    } else {
                        $transaction->title = $ledger->account->name;
                    }
                }
            }
            UnsetData($transaction, ['ledgers']);
            // if (in_array(33, $cashAccountId) || in_array(34, $cashAccountId)) {
            //    $transaction->transaction_ledgers=$transaction->ledgers->whereNotIn('account_id',$cashAccountId)->values();
            // }
            // UnsetData($transaction, ['ledgers']);
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
