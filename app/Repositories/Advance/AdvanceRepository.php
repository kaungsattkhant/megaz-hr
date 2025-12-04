<?php

namespace App\Repositories\Advance;

use App\Models\Advance;
use App\Models\AdvancePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceRepository implements AdvanceInterface
{

    public function list($data){
        $advances=Advance::orderBy('id','desc');
        if(isset($data['page'])){
            $perPage=$data['perPage'] ?? config('common.list_count');
            return $advances->paginate($perPage);
        }
        return $advances->get();
    }
    public function create($data)
    {
        DB::beginTransaction(); // start transaction

        try {
            $advance= Advance::create($data);
            DB::commit();
           return $advance;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs

            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function getAdvancePaymentDetailByAdvance($advanceId){
        $advancePayments=AdvancePayment::where('advance_id',$advanceId)->get();
        // if($advancePayments){
        //     \ResponseMessage('No one of advance payment of this advance',419);
        // }
        return $advancePayments;
    }
}