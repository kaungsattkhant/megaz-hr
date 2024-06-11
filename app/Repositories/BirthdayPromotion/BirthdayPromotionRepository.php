<?php

namespace App\Repositories\BirthdayPromotion;

use App\Models\BirthdayPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BirthdayPromotionRepository implements BirthdayPromotionRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $birthdayPromotions = BirthdayPromotion::paginate(config('common.list_count'));
        ResponseData($birthdayPromotions);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $birthdayPromotion = BirthdayPromotion::create($data);
            DB::commit();
            ResponseData($birthdayPromotion);
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
            $birthdayPromotion = BirthdayPromotion::find($id);
            if ($birthdayPromotion != null) {
                $birthdayPromotion->update($data);
                DB::commit();
                ResponseData($birthdayPromotion);
            } else {
                ResponseMessage('Birthday promotion not found', 404);
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
        try{
            $birthdayPromotion = BirthdayPromotion::find($id);
            if ($birthdayPromotion != null) {
                $birthdayPromotion->delete();
                DB::commit();
                ResponseMessage('Birthday promotion deleted successfully', 200);
            } else {
                ResponseMessage('Birthday promotion not found', 404);
            }
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
