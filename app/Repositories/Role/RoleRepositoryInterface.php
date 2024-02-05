<?php

namespace App\Repositories\Role;

interface RoleRepositoryInterface
{
    public function listAllData();

    public function createData(array $data);

    public function updateData(array $data,string $id);
}
