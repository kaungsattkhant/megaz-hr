<?php

namespace App\Repositories\Accrued;

use App\Models\Account;
use App\Models\Accrued;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class AccruedRepository implements AccruedRepositoryInterface
{
    public function getExpenseAccount($request)
    {
        return  Account::with('sub_account')->where(function ($query) {
            $query->where('account_code', 'like', '6-2%')
                ->orWhere('account_code', 'like', '6-3%')
                ->orWhere('account_code', 'like', '6-4%')
                ->orWhere('account_code', 'like', '6-5%')
                ->orWhere('account_code', 'like', '6-6%')
                ->orWhere('account_code', 'like', '6-7%')
                ->orWhere('account_code', 'like', '6-8%')
                ->orWhere('account_code', 'like', '6-9%');
        })->orderBy('account_code', 'asc')
            ->get();
    }
    public function createAccrued($request)
    {
        DB::beginTransaction();
        try {
            $expenseAccount = Account::where('id', $request['expense_account_id'])->where('account_code', $request['expense_account_code'])->first();
            $accruedAccount = Account::where('link_account_id', $expenseAccount->id)->first();

            $accrued = Accrued::create([
                'date_time' => now(),
                'category' => $request['category'],
                'type' => $request['type'],
                'account_id' => $accruedAccount->id,
                'main_account_id' => $expenseAccount->id,
                'cash_account_id' => $request['cash_account_id'] ?? null,
                'amount' => $request['amount'],
                'created_by' => UserData()->id,
            ]);
            if ($request['type'] == "addition") {
                $transaction = (new StoreTransactionLedger())->createTransaction([
                    'date' => now(),
                    'created_by' => UserData()->id,
                    'description' => 'Accrued',
                    'transactionable_id' => $accrued->id,
                    'is_confirmed' => 1,
                    'transactionable_type' => 'accrued',
                ]);
                #debit
                if ($expenseAccount) {
                    $debitAccount = (new Account())->accountByCode($expenseAccount->account_code);
                    if ($debitAccount) {
                        (new StoreTransactionLedger())->storeLedger([
                            'value' => $request['amount'],
                            'transaction_id' => $transaction->id,
                            'account_id' => $expenseAccount->id,
                            'action' => 'debit',
                        ]);
                    } else {
                        ResponseMessage('Account is Invalid', 419);
                    }
                }
                #credit
                if ($accruedAccount) {
                    (new StoreTransactionLedger())->storeLedger([
                        'value' => $request['amount'],
                        'transaction_id' => $transaction->id,
                        'account_id' =>  $accruedAccount->id,
                        'action' => 'credit',
                    ]);
                }
            } else if ($request['type'] == "settlement") {
                $transaction = (new StoreTransactionLedger())->createTransaction([
                    'date' => now(),
                    'created_by' => UserData()->id,
                    'description' => 'Settlement',
                    'transactionable_id' => $accrued->id,
                    'is_confirmed' => 1,
                    'transactionable_type' => 'accrued',
                ]);
                #debit
                if ($accruedAccount) {
                    $debitAccount = (new Account())->accountByCode($accruedAccount->account_code);
                    if ($debitAccount) {
                        (new StoreTransactionLedger())->storeLedger([
                            'value' => $request['amount'],
                            'transaction_id' => $transaction->id,
                            'account_id' => $accruedAccount->id,
                            'action' => 'debit',
                        ]);
                    } else {
                        ResponseMessage('Account is Invalid', 419);
                    }
                }
                #credit
                if ($request['cash_account_id']) {
                    (new StoreTransactionLedger())->storeLedger([
                        'value' => $request['amount'],
                        'transaction_id' => $transaction->id,
                        'account_id' => $request['cash_account_id'],
                        'action' => 'credit',
                    ]);
                }
            }
            DB::commit();
            return $accrued;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getAccrued($request)
    {
        $accrueds = DB::table('accrueds')
            ->join('accounts', 'accrueds.account_id', '=', 'accounts.id')
            ->select(
                'accrueds.account_id',
                'accounts.name as account_name',
                'accrueds.category',
                // DB::raw('SUM(CASE WHEN accrueds.type = "addition" THEN accrueds.amount ELSE 0 END) as total_addition'),
                // DB::raw('SUM(CASE WHEN accrueds.type = "settlement" THEN accrueds.amount ELSE 0 END) as total_settlement'),
                DB::raw('(SUM(CASE WHEN accrueds.type = "addition" THEN accrueds.amount ELSE 0 END) - SUM(CASE WHEN accrueds.type = "settlement" THEN accrueds.amount ELSE 0 END)) as total_balance')
            )
            ->groupBy('accrueds.account_id', 'accounts.name')
            ->paginate(config('common.list_count'));
        return $accrueds;
    }

    public function detailAccrued($accountId)
    {
        $accrueds = DB::table('accrueds')
            ->join('accounts', 'accrueds.account_id', '=', 'accounts.id')
            ->select(
                'accrueds.id',
                'accrueds.date_time AS date',
                'accrueds.type',
                'accrueds.category',
                'accounts.name AS accrued_name',
                'accrueds.amount',
                DB::raw('SUM(CASE WHEN accrueds.type = "addition" THEN accrueds.amount ELSE 0 END) 
                    OVER (PARTITION BY accrueds.account_id ORDER BY accrueds.date_time, accrueds.id) 
                - 
                SUM(CASE WHEN accrueds.type = "settlement" THEN accrueds.amount ELSE 0 END) 
                    OVER (PARTITION BY accrueds.account_id ORDER BY accrueds.date_time, accrueds.id)
                    AS balance')
            )
            ->where('accrueds.account_id', $accountId)
            ->orderBy('accrueds.id', 'desc')
            ->paginate(config('common.list_count'));

        return $accrueds;
    }
}
