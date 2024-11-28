<?php

namespace App\Repositories\Creditor;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class CreditorRepository implements CreditorInterface
{


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