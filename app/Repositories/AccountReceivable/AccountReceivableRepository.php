<?php

namespace App\Repositories\AccountReceivable;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\AccountReceivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountReceivableRepository implements AccountReceivableRepositoryInterface
{
    public function createAR(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['created_by'] = UserData()->id;
            $data['type'] = "ar";
            $data['date_time'] = CurrentTime();
            $ar = AccountReceivable::create($data);
            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => "account_receivable",
                'transactionable_id' => $ar->id,
                'transactionable_type' => 'account_receivable',
                'is_confirmed' => 1,
            ]);

            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $data['amount'],
                'transaction_id' => $transaction->id,
                'account_id' => $data['cash_account_id'],
                'action' => 'credit',
                'is_cashier_confirmed' => 0
            ]);

            $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                'value' => $data['amount'],
                'transaction_id' => $transaction->id,
                'account_id' => $data['account_id'],
                'action' => 'debit',
                'is_cashier_confirmed' => 0
            ]);

            DB::commit();
            ResponseData($ar);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseData($e->getMessage(), 422);
            throw $e;
        }
    }

    public function paidAr(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['date_time'] = CurrentTime();

            $account = Account::withSum(['accountReceivables as ar_sum' => function ($query) {
                $query->where('type', 'ar');
            }], 'amount')
            ->withSum(['accountReceivables as ar_paid_sum' => function ($query) {
                $query->where('type', 'ar_paid');
            }], 'amount')
            ->find($data['account_id']);

            $ar_balance = $account->ar_sum - $account->ar_paid_sum;

            if ($data['amount'] > $ar_balance) {
                ResponseMessage('Your amount is greater than the balance',422);
            }

            $data['type'] = "ar_paid";
            $data['created_by'] = UserData()->id;
            $paidAr = AccountReceivable::create($data);

            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => "account_receivable_paid",
                'transactionable_id' => $paidAr->id,
                'transactionable_type' => 'account_receivable_paid',
                'is_confirmed' => 1,
            ]);

            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $data['amount'],
                'transaction_id' => $transaction->id,
                'account_id' => $data['account_id'],
                'action' => 'credit',
                'is_cashier_confirmed' => 0
            ]);

            $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                'value' => $data['amount'],
                'transaction_id' => $transaction->id,
                'account_id' => $data['cash_account_id'],
                'action' => 'debit',
                'is_cashier_confirmed' => 0
            ]);

            DB::commit();
            ResponseData($paidAr);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }


    public function accountReceivableListDetail(int $id)
    {
        $accountWithAr = AccountReceivable::where('account_id', $id)->get();
        ResponseData($accountWithAr);
    }

    public function accountReceivableList(Request $request)
    {
        $accountReceivable = Account::withSum(['accountReceivables as ar_sum' => function ($query) {
            $query->where('type', 'ar');
        }], 'amount')
            ->withSum(['accountReceivables as ar_paid_sum' => function ($query) {
                $query->where('type', 'ar_paid');
            }], 'amount')
            ->withCount('accountReceivables')
            ->having('account_receivables_count', '>', 0)
            ->paginate(config('common.list_count'), ['id', 'account_code', 'name'])
            ->map(function ($account) {
                $account->ar_balance = $account->ar_sum - $account->ar_paid_sum;
                return $account;
            });
        ResponseData($accountReceivable);
    }
}
