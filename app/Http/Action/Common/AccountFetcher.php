<?php

namespace App\Http\Action\Common;

use App\Models\Account;
use App\Models\SubAccount;

class AccountFetcher
{
    public function getAccountByCode($code, $include_head_sub_accounts=false)
    {
        $query = Account::where('account_code', $code);
        if($include_head_sub_accounts){
            $query->with('sub_account.head_account');
        }
        $account = $query->first();
        if(!$account){
            ResponseMessage('Account with given code not found', 404);
        }

        return $account;
    }

    public function getAccountByName($name, $include_head_sub_accounts=false)
    {
        $query = Account::where('name', $name);
        if($include_head_sub_accounts){
            $query->with('sub_account.head_account');
        }
        $account = $query->first();
        if(!$account){
            ResponseMessage('Account with given code not found', 404);
        }

        return $account;
    }

    public function getAccountsBySubAccount($subAccountCode)
    {
        $subAccount = SubAccount::with('accounts')->where('account_code',$subAccountCode)->first();
        if(!$subAccount){
            ResponseMessage('Sub-Account with given code not found', 404);
        }
        $accounts = $subAccount->accounts;
        return $accounts;
    }
}
