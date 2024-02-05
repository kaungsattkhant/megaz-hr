<?php

namespace App\Repositories\Department;

use App\Models\Department;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function listAllData()
    {
        $departments = Department::all();
        return $departments;
    }

    public function createData(array $data)
    {
        $department = Department::create($data);
        return $department;
    }

    public function updateData(array $data, string $id)
    {
        if ($id) {
            $department = Department::find($id);
            $department->update($data);
        } else {
            $department = Department::create($data);
        }
        return $department;
    }
}
