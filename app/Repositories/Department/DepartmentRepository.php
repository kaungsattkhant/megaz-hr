<?php

namespace App\Repositories\Department;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Department::with('inventories.inventory','features')->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $departments = Department::with('inventories.inventory','features')->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'departments');
            $paginationData['departments'] = $departments;

            return $paginationData;
        } else {
            $departments = Department::with('inventories.inventory','features')->get();
            return $departments;
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $department = Department::create($data);
            $featureIds = json_decode($data['featureIds'], true);
            foreach ($featureIds as $feature) {
                $department->features()->attach($feature);
            }
            DB::commit();
            return $department;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $department = Department::find($id);
            if ($department) {
                $data = RemoveNullValues($data);
                $department->update($data);
                if (!empty($data['featureIds'])) {
                    $featureIds = json_decode($data['featureIds'],true);
                    $department->features()->sync($featureIds);
                }
            }

            DB::commit();
            return $department;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
