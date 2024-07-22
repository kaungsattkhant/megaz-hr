<?php

namespace App\Repositories\DeliveryCharge;

use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryChargeRepository implements DeliveryChargeRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $deliveryCharges = DeliveryCharge::select('delivery_charges.*')
            ->join(DB::raw('(SELECT MAX(id) as id FROM delivery_charges GROUP BY township_id) as latest_delivery_charges'), 'delivery_charges.id', '=', 'latest_delivery_charges.id')
            ->with('township')
            ->paginate(config('common.list'));

        return ResponseData($deliveryCharges);
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
