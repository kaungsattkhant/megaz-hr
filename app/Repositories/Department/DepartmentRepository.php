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
            $departments = Department::with('inventory.inventory','features')->orderBy('created_at','desc')->paginate(config('common.list_count'));
            return $departments;
        } else {
            $departments = Department::with('inventory.inventory','features')->get();
            foreach($departments as $department){
                // return $department;
            }
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
