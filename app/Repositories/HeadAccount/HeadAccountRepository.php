<?php

namespace App\Repositories\HeadAccount;

use App\Models\HeadAccount;
use App\Models\SubAccount;

class HeadAccountRepository implements HeadAccountInterface
{

    public function headAccountList($request){
        if($request->per_page || $request->page){
            return HeadAccount::where('is_available',1)->paginate(20);
        }
        return HeadAccount::where('is_available',1)->get();
    }

    public function updateOrCreateHeadAccount($request){

    }

    public function detailHeadAccount($headAccount)
    {
        return $headAccount;
        
    }

    public function subAccountList($request){
        if($request->per_page || $request->page){
            return SubAccount::with(['head_account'])->where('is_available',1)->paginate(20);
        }
        return SubAccount::with(['head_account'])->where('is_available',1)->get();
    }

    public function updateOrCreateSubAccount($request){

    }

    public function detailSubAccount($subAccount){
        $subAccount->head_account=$subAccount->head_account;
        return $subAccount;
    }
}
