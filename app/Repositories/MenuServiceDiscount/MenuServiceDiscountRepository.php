<?php

namespace App\Repositories\MenuServiceDiscount;

use App\Models\MenuServiceDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuServiceDiscountRepository implements MenuServiceDiscountRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $msd = MenuServiceDiscount::with('discountable')->paginate(config('common.list_count'));
        foreach ($msd as $item) {
            if ($item->discountable_type == 'menu') {
                $item->discountable->load('prices');
            }
        }
        ResponseData($msd);
    }

    public function createData(array $data)
    {
        dd($data);
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $validate = $this->validateMSD($data, $data['from_date'], $data['to_date']);
            if($validate==true)
            {
                ResponseMessage('Menu Service Discount dates overlap with existing discounts for the specified rooms.', 422);
            }
            $msd = MenuServiceDiscount::create($data);
            DB::commit();
            ResponseData($msd);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function validateMSD($data, $fromDate, $toDate)
    {
        $overlappingMSD = MenuServiceDiscount::where('type', $data['type'])->where('discountable_type', $data['discountable_type'])->where('discountable_id', $data['discountable_id'])
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->where('from_date', '<=', $toDate)
                    ->where('to_date', '>=', $fromDate);
            })->first();
        if ($overlappingMSD == null) {
            return false;
        }
        return true;
    }

    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $msd = MenuServiceDiscount::find($id);
            $msd->update($data);
            DB::commit();
            ResponseData($msd);
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
            $msd = MenuServiceDiscount::find($id);
            $msd->delete();
            DB::commit();
            Responsemessage("Menu Service Discount deleted");
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
