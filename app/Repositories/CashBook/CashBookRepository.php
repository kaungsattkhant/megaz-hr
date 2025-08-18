<?php

namespace App\Repositories\CashBook;

use stdClass;
use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\Transaction;
use App\Models\CashbookBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Http\Action\Transaction\CashBookTransaction;


class CashBookRepository implements CashBookInterface
{
    public function list($request)
    {
        $isPos = $request->is_pos;
        // $is_closing_column = 'is_pos_closing';
        // $closing_date_column = 'pos_closing_date';

        $is_closing_column = null;
        $closing_date_column = null;
        if ($isPos) {
            $is_closing_column = 'is_pos_closing';
            $closing_date_column = 'pos_closing_date';
        }
        if (!$isPos) {
            $is_closing_column = 'is_closing';
            $closing_date_column = 'closing_date';
        }
        if ($is_closing_column == null && $closing_date_column == null) {
            ResponseMessage('Something went wrong in cashbook', 419);
        }
        $cashAccountId = $request->cash_account_id;
        $latestClosedTransaction = (new CashBookTransaction())->getLatestClosedTransaction($request, $cashAccountId, $is_closing_column);
        // dd($latestClosedTransaction->$closing_date_column);
        $cashbookTransactions = Transaction::with(['ledgers.account', 'transactionable'])
            ->isConfirmed(1)
            ->select(['id', 'date', 'description', 'transactionable_id', 'transactionable_type'])
            ->whereHas('ledgers', function ($query) use ($cashAccountId) {
                $query->whereIn('account_id', $cashAccountId);
            })
            ->when(($request->from_date == null && $request->to_date == null) && $latestClosedTransaction, function ($q) use ($latestClosedTransaction, $closing_date_column) {
                $q->where('created_at', '>', $latestClosedTransaction->$closing_date_column);
            })
            ->get();
        $current_debit_amount = $current_credit_amount = 0;
        foreach ($cashbookTransactions as $transaction) {
            if ($transaction->transactionable_type == 'invoice') {
                // dd($transaction->transactionable);
            }
            foreach ($transaction->ledgers as $ledger) {
                if (in_array(config('common.pos_cash'), $cashAccountId) && in_array($ledger->account_id, $cashAccountId)) {
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
        }
        $balance = (new CashBookTransaction())->getDailyOpeningBalance($request);
        $data = new stdClass();
        $data->opening_balance = $balance->opening_balance;
        $data->remaining_balance = ($balance->opening_balance + $current_debit_amount) - $current_credit_amount;
        $data->cashbook_list = $cashbookTransactions;
        return $data;
    }

    public function closeTransaction($request)
    {
        DB::beginTransaction();
        try {
            $isPos = $request->is_pos;
            // $is_closing_column = 'is_closing';
            // $closing_date_column = 'closing_date';
            $is_closing_column = null;
            $closing_date_column = null;
            if ($isPos) {
                $is_closing_column = 'is_pos_closing';
                $closing_date_column = 'pos_closing_date';
            }
            if (!$isPos) {
                $is_closing_column = 'is_closing';
                $closing_date_column = 'closing_date';
            }
            if ($is_closing_column == null && $closing_date_column == null) {
                ResponseMessage('Something went wrong in cashbook', 419);
            }

            $cashAccountId = $request->cash_account_id;
            if ($request->is_pos && $request->to_cash_account_id == null) {
                ResponseMessage('To cash account is requried ', 419);
            }
            $toCashAccountId = isset($request->to_cash_account_id) ? $request->to_cash_account_id : null;
            // $openingBalance = (new CashBookTransaction())->getOpeningBalance($request);
            $openingBalance = (new CashBookTransaction())->getDailyOpeningBalance($request);
            $closingBalance = (new CashBookTransaction())->getDailyClosingBalance($openingBalance->opening_balance, $request);
            $cashInBalance = (new CashBookTransaction())->getCashInBalance(today(), $request->cash_account_id);
            // $closingBalance = (new CashBookTransaction())->getClosingBalance($openingBalance->opening_balance, $request);
            // $cashbookBalance = CashbookBalance::updateOrCreate(
            //     [
            //         'year' => now()->year,
            //         'month' => now()->month,
            //         'cash_account_id' => $cashAccountId,
            //     ],
            //     [
            //         'year' => now()->year,
            //         'month' => now()->month,
            //         'opening_balance' => $openingBalance->opening_balance,
            //         'closing_balance' => $closingBalance,
            //         'cash_account_id' => $cashAccountId,
            //     ]
            // );
            $cashbookBalance = CashbookBalance::create(
                [
                    'year' => now()->year,
                    'month' => now()->month,
                    'opening_balance' => $openingBalance->opening_balance,
                    'closing_balance' => $closingBalance,
                    'cash_account_id' => $cashAccountId,
                ]
            );

            $latestTransaction = Transaction::
                whereHas('ledgers', function ($query) use ($cashAccountId) {
                    $query->where('account_id', $cashAccountId); #transaction close depend on transaction
                })
                ->isConfirmed(1)
                ->whereDate('created_at', today())
                ->first();
            if ($latestTransaction->$is_closing_column) {
                ResponseMessage('Cashbook is already closed', 419);
            }
            if (isset($request->is_pos) && $request->is_pos) {
                (new CashBookTransaction())->transferDailyCash($cashAccountId, $toCashAccountId, $cashbookBalance->id, $cashInBalance);
            }
            if ($latestTransaction) {
                $latestTransaction->$is_closing_column = 1;
                $latestTransaction->$closing_date_column = now();
                $latestTransaction->save();
            }

            if ($cashbookBalance) {
                DB::commit();
                ResponseMessage('Transaction closing is successfully', 200);
            }
            ResponseMessage('Something wrong', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
        // ResponseMessage('Transaction closing is fail', 422);
    }

    public function getCashbookClosingHistory($request)
    {
        $isPos = $request->is_pos;
        $date = isset($request->date) || $request->date != null ? Carbon::parse($request->date) : today();
        // $is_closing_column = 'is_pos_closing';
        // $closing_date_column = 'pos_closing_date';

        $is_closing_column = null;
        $closing_date_column = null;
        if ($isPos) {
            $is_closing_column = 'is_pos_closing';
            $closing_date_column = 'pos_closing_date';
        }
        if (!$isPos) {
            $is_closing_column = 'is_closing';
            $closing_date_column = 'closing_date';
        }
        if ($is_closing_column == null && $closing_date_column == null) {
            ResponseMessage('Something went wrong in cashbook', 419);
        }
        $account_codes = $request->is_pos ? Config::get('common.pos_cash_account_code') : Config::get('common.cash_account_code');
        // $closingCashbook=Ledger::with(['account','transaction:id,closing_date,is_closing,pos_closing_date,is_pos_closing'])->whereHas('account',function($query)use($account_codes){
        //     $query->whereIn('account_code',$account_codes);
        // })
        // ->whereHas('transaction',function($q)use($is_closing_column,$closing_date_column,$date){
        //     $q->where($is_closing_column,1)
        // ->whereDate($closing_date_column,$date);
        // })->paginate(20);
        $closingCashbook = CashbookBalance::with(['account'])
            ->whereHas('account', function ($query) use ($account_codes) {
                $query->whereIn('account_code', $account_codes);
            })
            ->whereDate('created_at', $date)
            ->get();
        return $closingCashbook;
    }


}
