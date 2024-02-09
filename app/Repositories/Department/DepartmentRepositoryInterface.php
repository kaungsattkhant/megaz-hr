<?php

namespace App\Repositories\Department;

use Illuminate\Http\Request;

interface DepartmentRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);
}
