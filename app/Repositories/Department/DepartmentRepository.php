<?php

namespace App\Repositories\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->all_minified){
            return Department::all();
        }
        $allDepartments = Department::all();
        $departments = Pagination($allDepartments,$request,'departments');
        return $departments;
    }

    public function createData(array $data)
    {
        $department = Department::create($data);
        return $department;
    }

    public function updateData(array $data, int $id)
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
