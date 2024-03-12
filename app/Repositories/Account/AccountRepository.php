<?php

namespace App\Repositories\Account;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountRepository implements AccountInterface
{

    public function list($request){
        if($request->per_page || $request->page){
            return Account::where('is_available',1)->paginate(20);
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

}
