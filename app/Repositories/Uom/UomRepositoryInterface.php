<?php

namespace App\Repositories\Uom;

use Illuminate\Http\Request;

interface UomRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);
}
