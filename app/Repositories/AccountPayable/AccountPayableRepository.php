<?php

namespace App\Repositories\AccountPayable;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class AccountPayableRepository implements AccountPayableInterface
{
    public function list($request)
    {
        // $ledger=Ledger::join('accounts','ledgers.account_id','accounts.id')
        // ->join('sub_accounts','accounts.sub_account_id','sub_accounts.id')
        // ->where('sub_accounts.head_account_id',config('common.liabilities'))
        // ->where('ledgers.personable_type','supplier')
        // ->select('ledgers.id','ledgers.personable_id as supplier_id',
        // DB::raw('SUM(CASE WHEN action = "debit" THEN value ELSE 0 END) as debit_amount'),
        // DB::raw('SUM(CASE WHEN action = "credit" THEN value ELSE 0 END) as credit_amount'),
        // )
        // ->groupBy('ledgers.personable_id')
        // ->get();
        $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
            ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
            ->where('sub_accounts.head_account_id', config('common.liabilities'))
            ->where('ledgers.personable_type', 'supplier')
            ->select(
                'ledgers.personable_id as supplier_id',
                'suppliers.account_id',
                'suppliers.name as supplier_name',
                DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
                DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
                DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
            )
            ->groupBy('ledgers.personable_id')
            ->get();
        return $ledger;
    }
    public function createPayableAccount($request)
    {
        $latestAccount = Account::where('sub_account_id', $request->sub_account_id)
            ->orderByRaw("CAST(SUBSTRING_INDEX(account_code, '-', -1) AS UNSIGNED) DESC")
            ->first();
        // ->max('account_code');

        if ($latestAccount) {
            $latestAccountCodeNo = explode('-', $latestAccount->account_code);
            // dd($account_code_no[1]);
            $new_account_code = (int) $latestAccountCodeNo[1] + 1;
            $code = $latestAccountCodeNo[0] . '-' . $new_account_code;
            $account = Account::create([
                'name' => $request->name,
                'account_code' => $code,
                'sub_account_id' => $request->sub_account_id,
            ]);
            return $account;
        }
        ResponseMessage('Something is wrong', 419);
    }

    public function getPayableAccount()
    {
        return Account::whereHas('sub_account.head_account', function ($q) {
            $q->where('head_account_id', config('common.liabilities'));
        })
            ->get();
    }

    public function createPayableTransaction($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['created_by'] = UserData()->id;
            $data['is_confirmed'] = 1;
            $transaction = (new StoreTransactionLedger())->createTransaction($data);
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'date' => now(),
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'personable_id'=>$request->supplier_id,
                'personable_type'=>'supplier',
                'action' => 'credit',
            ]);

            #debit
            $debitLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->account_id,
                'personable_id'=>$request->supplier_id,
                'personable_type'=>'supplier',
                'action' => 'debit',
            ]);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function listOfAccountPayableTransaction($request)
    {
        $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
        ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
        ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
        ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
        ->where('sub_accounts.head_account_id', config('common.liabilities'))
        ->where('ledgers.personable_type', 'supplier')
        ->whereNull('transactions.transactionable_id')
        ->select(
            // 'ledgers.created_at as date',
            DB::raw("DATE_FORMAT(ledgers.created_at, '%M %d %Y %H:%i') as date"),
            'ledgers.personable_id as supplier_id',
            'suppliers.account_id',
            'suppliers.name as supplier_name',
            'ledgers.value as amount'
            // DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
            // DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
            // DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
        )
        // ->groupBy('ledgers.personable_id')
        ->get();
        return $ledger;
    }

}
