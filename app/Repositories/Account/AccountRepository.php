<?php

namespace App\Repositories\Account;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Support\Facades\DB;

class AccountRepository implements AccountInterface
{

    public function list($request){
        if($request->per_page || $request->page){
           return  DB::table('accounts')
            ->leftJoin('ledgers', 'accounts.id', '=', 'ledgers.account_id')
            ->select('accounts.id','accounts.name','accounts.account_code',
                     DB::raw('COALESCE(SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END), 0) as debit_amount'),
                     DB::raw('COALESCE(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END), 0) as credit_amount'))
            ->groupBy('accounts.id', 'accounts.name')
            ->paginate(config('common.list_count'));
        }
        return Account::where('is_available',1)->get();
    }

    public function updateOrCreate($request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $account=Account::updateOrCreate(
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

    public function getSubAccountByHeadAccount($head_account_id){
        $sub_account=SubAccount::where('head_account_id',$head_account_id)->get();
        return $sub_account;
    }

    public function getCashAccount(){
        $sub_account=Account::whereHas('sub_account',function($q){
            $q->where('name','Cash & Bank');
        })->get();
        return $sub_account;    
    }
    
    public function accountBySubAccount($sub_account_id){
        return Account::where('sub_account_id',$sub_account_id)->get();
    }

    
}
