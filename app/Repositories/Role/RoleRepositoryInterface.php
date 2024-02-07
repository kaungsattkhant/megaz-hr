<?php

namespace App\Repositories\Role;

use Illuminate\Http\Request;

interface RoleRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,string $id);
}
