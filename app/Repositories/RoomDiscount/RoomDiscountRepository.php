<?php

namespace App\Repositories\RoomDiscount;

use App\Models\RoomDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomDiscountRepository implements RoomDiscountRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $roomDiscounts = RoomDiscount::with('room')->paginate(config('common.list_count'));
        ResponseData($roomDiscounts);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $roomDiscount = RoomDiscount::create($data);
            DB::commit();
            ResponseData($roomDiscount);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $roomDiscount = RoomDiscount::find($id);
            $roomDiscount->update($data);
            DB::commit();
            ResponseData($roomDiscount);
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
            $roomDiscount = RoomDiscount::find($id);
            $roomDiscount->delete();
            DB::commit();
            Responsemessage("Room Discount deleted");
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
