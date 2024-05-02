<?php

namespace App\Repositories\Area;

use Illuminate\Http\Request;

interface AreaRepositoryInterface
{
    public function getAreas(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function getAreaByAreaType(int $id);

    public function getAreaByAreaCategory(int $id);
}
