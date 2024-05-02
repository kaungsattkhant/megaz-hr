<?php

namespace App\Repositories\Staff;

use App\Models\Staff;
use App\Models\StaffEmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffRepository implements StaffRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $departmentId = $request->department_id;
            return Staff::orderByDesc('id')
                ->with(['department', 'roles'])
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->when($departmentId, function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })
                ->paginate(config('common.list_count'));
        } else {
            $staffs = Staff::where('is_active', 1)->get();

            return $staffs;
        }
    }

    public function createData(array $data)
{
        DB::beginTransaction();
        try {
            $data['is_active'] = 1;
            $data = RemoveNullValues($data);
            $staff = Staff::create($data);

            if($data['department_id'] == 6)
            {
                $inventoryIds = isset($data['inventoryIds']) ? json_decode($data['inventoryIds']) : [];
                if (is_array($inventoryIds)) {
                    foreach ($inventoryIds as $inventoryId) {
                        $staff->inventories()->attach($inventoryId);
                    }
                }
            }

            if (isset($data['roles']) && is_array($data['roles'])) {
                $staff->roles()->attach($data['roles']);
            }
            $data['staff_id'] = $staff->id;
            $this->createEmegercyContact($data);
            DB::commit();
            return $staff;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createEmegercyContact($data)
    {
        $staff = StaffEmergencyContact::where('staff_id',$data['staff_id'])->first();
        if(!$staff)
        {
            $staff = StaffEmergencyContact::create($data);
        }
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $staff = Staff::find($id);
            if ($staff) {

                $data = RemoveNullValues($data);

                $staff->update($data);
                if (isset($data['roles'])) {
                    $staff->roles()->sync($data['roles']);
                }
            }
            DB::commit();
            return  $staff;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteData($id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->is_active = 0;
            $staff->save();

            return true;
        }

        return false;
    }

    public function getStaffByDepartment(Request $request, int $departmentId)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Staff::where('department_id', $departmentId)->where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $staffs = Staff::with('department')->where('department_id', $departmentId)
                ->where('is_active', 1)
                ->skip($skip)->take($perPage)
                ->get();
            $staffData = MakePaginationData($request, $totalCount, 'staffs', $staffs);

            return $staffData;
        } else {
            $staffs = Staff::with('department')->where('department_id', $departmentId)
                ->where('is_active', 1)
                ->get();

            return $staffs;
        }
    }
}
