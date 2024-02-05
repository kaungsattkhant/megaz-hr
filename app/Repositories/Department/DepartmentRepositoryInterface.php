<?php

namespace App\Repositories\Department;

interface DepartmentRepositoryInterface
{
    public function listAllData();

    public function createData(array $data);

    public function updateData(array $data,string $id);
}
