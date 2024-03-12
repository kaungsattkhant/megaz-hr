<?php

namespace App\Repositories\HeadAccount;

use App\Models\SubAccount;
use App\Models\HeadAccount;
use Illuminate\Support\Facades\DB;

class HeadAccountRepository implements HeadAccountInterface
{

    public function headAccountList($request){
        if($request->per_page || $request->page){
            return HeadAccount::where('is_available',1)->paginate(20);
        }
        return HeadAccount::where('is_available',1)->get();
    }

    public function updateOrCreateHeadAccount($request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            $headAccount=HeadAccount::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $headAccount;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
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
        $data = $request->all();
        DB::beginTransaction();
        try {
            $subAccount=HeadAccount::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $subAccount;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detailSubAccount($subAccount){
        $subAccount->head_account=$subAccount->head_account;
        return $subAccount;
    }
}
