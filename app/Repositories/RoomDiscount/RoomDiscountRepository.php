<?php

namespace App\Repositories\RoomDiscount;

use App\Models\RoomDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomDiscountRepository implements RoomDiscountRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $roomDiscounts = RoomDiscount::with('rooms')->orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        ResponseData($roomDiscounts);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $roomIds = json_decode($data['roomIds']);
            // $isValid = $this->validateRoomDiscountDates($roomIds, $data['from_date'], $data['to_date']);
            // if ($isValid==true) {
            //     ResponseMessage('RoomDiscount dates overlap with existing discounts for the specified rooms.', 422);
            // }
            $roomDiscount = RoomDiscount::create($data);
            if ($data['roomIds']) {
                $roomIds = json_decode($data['roomIds']);
                foreach ($roomIds as $roomId) {
                    $roomDiscount->rooms()->attach($roomId);
                }
            }
            DB::commit();
            ResponseData($roomDiscount);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function validateRoomDiscountDates(array $roomIds, $fromDate, $toDate)
    {
        foreach ($roomIds as $roomId) {
            $overlappingDiscount = RoomDiscount::whereHas('rooms', function ($query) use ($roomId) {
                $query->where('entities.id', $roomId);
            })
                ->where(function ($query) use ($fromDate, $toDate) {
                    $query->where('from_date', '<=', $toDate)
                        ->where('to_date', '>=', $fromDate);
                })->first();

            if ($overlappingDiscount == null) {
                return false;
            }
        }

        return true;
    }




    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $roomDiscount = RoomDiscount::find($id);
            $roomDiscount->update($data);
            if ($data['roomIds']) {
                $roomIds = json_decode($data['roomIds']);
                $roomDiscount->rooms()->sync($roomIds);
            }
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
    public function getRoomDiscountList($roomId)
    {
        $currentDate = now();
        $roomDiscountList = RoomDiscount::whereHas('rooms', function ($query) use ($roomId) {
            $query->where('entities.id', $roomId);
        })
            ->where(function ($query) use ($currentDate) {
                $query->whereDate('from_date', '<=', $currentDate)
                    ->whereDate('to_date', '>=', $currentDate);
            })
            ->get();
        return $roomDiscountList;
    }
}
