<?php

namespace App\Repositories\Entity;

use Illuminate\Http\Request;

interface EntityRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);
}
