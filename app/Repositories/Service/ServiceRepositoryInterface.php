<?php

namespace App\Repositories\Service;

use Illuminate\Http\Request;

interface ServiceRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);
}
