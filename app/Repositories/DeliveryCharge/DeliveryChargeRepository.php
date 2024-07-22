<?php

namespace App\Repositories\DeliveryCharge;

use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryChargeRepository implements DeliveryChargeRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $deliveryCharges = DeliveryCharge::paginate(config('common.list'));
        ResponseData($deliveryCharges);
    }

    public function createData(Request $request)
    {
        DB::beginTransaction();
        try{
            $deliveryCharges = DeliveryCharge::create($request->all());
            DB::commit();
            ResponseData($deliveryCharges);
        }catch(\Exception $e)
        {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
