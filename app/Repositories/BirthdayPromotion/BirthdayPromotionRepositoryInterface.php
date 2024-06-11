<?php

namespace App\Repositories\BirthdayPromotion;

use Illuminate\Http\Request;

interface BirthdayPromotionRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);
}
