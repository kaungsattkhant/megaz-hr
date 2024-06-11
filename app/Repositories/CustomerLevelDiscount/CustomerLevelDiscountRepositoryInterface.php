<?php

namespace App\Repositories\CustomerLevelDiscount;

use Illuminate\Http\Request;

interface CustomerLevelDiscountRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);
}
