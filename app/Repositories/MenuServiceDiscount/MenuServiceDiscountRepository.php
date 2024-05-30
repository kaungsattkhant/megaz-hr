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
        if ($item->discountable instanceof \App\Models\Menu) {
            $item->discountable->load('prices');
        }
    }

    return ResponseData($msd);
}



    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $msd = MenuServiceDiscount::create($data);
            DB::commit();
            ResponseData($msd);
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
