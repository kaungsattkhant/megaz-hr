<?php

namespace App\Repositories\Creditor;

use App\Models\Ledger;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class CreditorRepository implements CreditorInterface
{

    public function list($request)
    {
        // config('common.creditor_account_code')
        $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
            ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
            ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->where('sub_accounts.account_code',config('common.creditor_account_code') )
            ->where('ledgers.personable_type', 'supplier')
            ->select(
                'ledgers.personable_id as supplier_id',
                'suppliers.creditor_account_id',
                'suppliers.name as supplier_name',
                DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
                DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
                DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
            )
            ->groupBy('ledgers.personable_id')
            ->get();
        return $ledger;
    }

    public function createCreditorAccount($request)
    {
        DB::beginTransaction();
        try {

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
                DB::commit();
                return $account;
            }
            ResponseMessage('Something is wrong', 419);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getCreditorAccountList()
    {
        $creditorAccountCode = config('common.creditor_account_code');
        return Account::orderBy('accounts.id', 'asc')
            ->whereHas('sub_account', function ($q) use ($creditorAccountCode) {
                $q->where('account_code', $creditorAccountCode);
            })
            ->get();
    }
}