<?php

namespace App\Repositories\RoomDiscount;

use App\Models\RoomDiscount;
use Illuminate\Http\Request;

class RoomDiscountRepository implements RoomDiscountRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $roomDiscounts = RoomDiscount::paginate(config('common.list_count'));
        ResponseData($roomDiscounts);
    }

    public function createData(array $data)
    {
        $data['created_by'] = UserData()->id;
        $roomDiscount = RoomDiscount::create($data);
        ResponseData($roomDiscount);
    }

    public function editData(int $id, array $data)
    {
        $roomDiscount = RoomDiscount::find($id);
        $roomDiscount->update($data);
        ResponseData($roomDiscount);
    }

    public function deleteData(int $id)
    {
        $roomDiscount = RoomDiscount::find($id);
        $roomDiscount->delete();
        Responsemessage("Room Discount deleted");
    }
}
