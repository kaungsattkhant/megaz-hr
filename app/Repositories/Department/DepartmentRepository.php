<?php

namespace App\Repositories\Department;

use App\Models\Staff;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $departments = Department::with('inventory.inventory', 'features', 'roles')->orderBy('created_at', 'desc')->paginate(config('common.list_count'));
            return $departments;
        } else {
            $departments = Department::with('inventory.inventory', 'features', 'roles')->get();
            foreach ($departments as $department) {
                // return $department;
            }
            return $departments;
        }
    }

    public function getDepartments()
    {
        return Department::get();
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['slug'] = Str::slug($data['name'], '-');
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
                    $departmentFeatureIds = json_decode($data['featureIds'], true);

                    // Update department features
                    $department->features()->sync($departmentFeatureIds);

                    // Get all staff under this department
                    $staffList = Staff::where('department_id', $id)->with('features')->where('id', 211)->get();

                    foreach ($staffList as $staff) {
                        $staffFeatureIds = $staff->features->pluck('id')->toArray();
                        // Keep only features that still exist in department
                        $allowedFeatures = array_values(
                            array_intersect($staffFeatureIds, $departmentFeatureIds)
                        );
                        // Update staff features
                        $staff->features()->sync($allowedFeatures);
                    }
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
