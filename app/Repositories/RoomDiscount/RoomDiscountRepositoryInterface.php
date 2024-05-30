<?php

namespace App\Repositories\RoomDiscount;

use Illuminate\Http\Request;

interface RoomDiscountRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function editData(int $id, array $data);

    public function deleteData(int $id);
}
