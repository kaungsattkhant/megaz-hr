<?php

namespace App\Repositories\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Department::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $departments = Department::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'departments');
            $paginationData['departments'] = $departments;

            return $paginationData;
        }
        else{
            $departments = Department::all();

            return $departments;
        }
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
