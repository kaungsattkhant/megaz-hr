<?php

namespace App\Repositories\CustomerLevelDiscount;

use App\Models\CustomerLevelDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLevelDiscountRepository implements CustomerLevelDiscountRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $customerLevelDiscounts = CustomerLevelDiscount::paginate(config('common.list_count'));
        ResponseData($customerLevelDiscounts);
    }


    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $customerLevelDiscount = CustomerLevelDiscount::create($data);
            DB::commit();
            ResponseData($customerLevelDiscount);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $customerLevelDiscount = CustomerLevelDiscount::find($id);
            if ($customerLevelDiscount != null) {
                $customerLevelDiscount->update($data);
                DB::commit();
                ResponseData($customerLevelDiscount);
            } else {
                ResponseMessage('Customer Level Discount not found', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        DB::beginTransaction();
        try {
            $customerLevelDiscount = CustomerLevelDiscount::find($id);
            if ($customerLevelDiscount != null) {
                $customerLevelDiscount->delete();
                DB::commit();
                ResponseMessage('Customer Level Discount deleted successfully', 200);
            } else {
                ResponseMessage('Customer Level Discount not found', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
