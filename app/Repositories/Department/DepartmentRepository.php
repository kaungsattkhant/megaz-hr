<?php

namespace App\Repositories\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allDepartments = Department::all();
        $departments = Pagination($allDepartments,$request,'departments');
        return $departments;
    }

    public function createData(array $data)
    {
        $department = Department::create($data);
        return $department;
    }

    public function updateData(array $data, string $id)
    {
        $department = Department::find($id);
        if($department)
        {
            $data = RemoveNullValues($data);
            $department->update($data);
        }
        return $department;
    }
}
