<?php

namespace App\Repositories\Account;

use App\Models\Account;
use App\Models\SubAccount;
use App\Models\ThirdAccount;
use App\Models\SecondAccount;
use Illuminate\Support\Facades\DB;

class AccountRepository implements AccountInterface
{

    public function list($request)
    {
        if ($request->per_page || $request->page) {
            return DB::table('accounts')
                ->leftJoin('ledgers', 'accounts.id', '=', 'ledgers.account_id')
                ->leftJoin('transactions', 'transactions.id', '=', 'ledgers.transaction_id')
                ->leftJoin('sub_accounts', 'sub_accounts.id', '=', 'accounts.sub_account_id')
                ->where('transactions.is_confirmed', 1)
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('accounts.name', 'LIKE', '%' . $request->search_input . '%')
                        ->orWhere('accounts.account_code', 'LIKE', '%' . $request->search_input . '%');
                })
                ->select('accounts.is_active', 'accounts.id', 'accounts.name', 'accounts.account_code', 'accounts.sub_account_id', 'sub_accounts.name as sub_account_name',
                    DB::raw('COALESCE(SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END), 0) as debit_amount'),
                    DB::raw('COALESCE(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END), 0) as credit_amount'))
                ->groupBy('accounts.id', 'accounts.name')
                ->paginate(config('common.list_count'));
        }
        return Account::where('is_available', 1)->get();
    }

    public function updateOrCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $account = Account::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($account)
    {
        return $account;
    }

    public function getSubAccountByHeadAccount($head_account_id)
    {
        $sub_account = SubAccount::where('head_account_id', $head_account_id)->get();
        return $sub_account;
    }

    public function getCashAccount()
    {
        $sub_account = Account::whereHas('sub_account', function ($q) {
            $q->where('name', 'Cash & Bank');
        })->get();
        return $sub_account;
    }

    public function accountBySubAccount($sub_account_id)
    {
        return Account::where('sub_account_id', $sub_account_id)->get();
    }


    public function getSecondAccount($account_id){
        $second_account=Account::where('account_id',$account_id)->where('type','is_second')->get();
        return $second_account;
    }

    public function getThirdAccount($account_id){
        $account=Account::where('account_id',$account_id)->where('type','is_third')->get();
        return $account;
        // $third_account=ThirdAccount::whereHas('second_account.account',function($q)use($sub_account_id){
        //     $q->where('sub_account_id',$sub_account_id);
        // })->get();
        // return $third_account;
    }

    public function createSecondAccount($request)
    {
        $latestAccount = Account::where('account_id', $request->account_id)
            ->orderByRaw("CAST(SUBSTRING_INDEX(account_code, '-', -1) AS UNSIGNED) DESC")
            ->first();
        if ($latestAccount) {
            $latestAccountCodeNo = explode('-', $latestAccount->account_code);
            // dd($account_code_no[1]);
            $new_account_code = (int) $latestAccountCodeNo[2] + 1;
            $code = $latestAccountCodeNo[0] . '-' . $latestAccountCodeNo[1] . '-' . $new_account_code;
        } else {
            $code = $request->original_account_code . '-' . "1";
        }
        DB::beginTransaction();
        try {

            $account = Account::create([
                'name' => $request->name,
                'account_code' => $code,
                'account_id' => $request->account_id,
                'sub_account_id' => $request->sub_account_id,
                'type'=>'is_second',
            ]);
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createThirdAccount($request)
    {
        $latestAccount = Account::where('account_id', $request->account_id)
            ->orderByRaw("CAST(SUBSTRING_INDEX(account_code, '-', -1) AS UNSIGNED) DESC")
            ->first();
        if ($latestAccount) {
            $latestAccountCodeNo = explode('-', $latestAccount->account_code);
            // dd($account_code_no[1]);
            $new_account_code = (int) $latestAccountCodeNo[3] + 1;
            $code = $latestAccountCodeNo[0] . '-' . $latestAccountCodeNo[1] . '-'.$latestAccountCodeNo[2] . '-' . $new_account_code;
        } else {
            $code = $request->original_account_code . '-' . "1";
        }
        DB::beginTransaction();
        try {
            $account = Account::create([
                'name' => $request->name,
                'account_code' => $code,
                'account_id' => $request->account_id,
                'sub_account_id'=>$request->sub_account_id,
                'type'=>'is_third',
            ]);
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

}
