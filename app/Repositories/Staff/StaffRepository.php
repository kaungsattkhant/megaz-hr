<?php

namespace App\Repositories\Staff;

use App\Models\Feature;
use App\Models\Inventory;
use App\Models\Role;
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

            if ($data['department_id'] == 6) {
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

            if(isset($data['featureIds']))
            {
                $featureIds = json_decode($data['featureIds']);
                foreach ($featureIds as $featureId) {
                    $staff->features()->attach($featureId);
                }
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
        $staff = StaffEmergencyContact::where('staff_id', $data['staff_id'])->first();
        if (!$staff) {
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
                $staff->emergencyContacts()->updateOrCreate(['staff_id' => $staff->id], $data);
                if($staff->department_id != $data['department_id'])
                {
                    $staff->roles()->detach();
                }
                $staff->update($data);

                if (isset($data['roles']) && $data['roles'] !== null) {
                    $rolesToAttach = $data['roles'];
                    $currentRoles = $staff->roles()->pluck('id')->toArray();
                    $rolesToAttach = array_diff($rolesToAttach, $currentRoles);
                    if (!empty($rolesToAttach)) {
                        $staff->roles()->attach($rolesToAttach);
                    }
                }

                if (isset($data['inventoryIds']) && $data['inventoryIds'] !== null) {
                    $inventoryIdsToAttach = json_decode($data['inventoryIds'], true);
                    $currentInventoryIds = $staff->inventories()->pluck('id')->toArray();
                    $inventoryIdsToAttach = array_diff($inventoryIdsToAttach, $currentInventoryIds);
                    if (!empty($inventoryIdsToAttach)) {
                        $staff->inventories()->attach($inventoryIdsToAttach);
                    }
                }

                if (isset($data['featureIds']) && $data['featureIds'] !== null) {
                    $featuireIds = json_decode($data['featureIds'], true);
                    $currentFeatureList = $staff->features()->pluck('id')->toArray();
                    $featuireIds = array_diff($featuireIds, $currentFeatureList);
                    if (!empty($featuireIds)) {
                        $staff->features()->attach($featuireIds);
                    }
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

    public function staffDetail(int $id)
    {
        $staff = Staff::with('department', 'roles', 'inventories', 'emergencyContacts', 'gender', 'completed_tasks','features')->find($id);
        if ($staff == null) {
            ResponseMessage("Staff not found or invalid id", 404);
        }
        return $staff;
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

    public function deleteStaffRole(int $staff_id, int $role_id)
    {
        DB::beginTransaction();
        try {
            $staff = Staff::find($staff_id);
            $role = Role::find($role_id);
            if (!$staff || !$role) {
                ResponseMessage('Staff or Role not found');
            } else {
                $staff->roles()->detach($role->id);
                DB::commit();
                ResponseMessage("Role detach successfully");
            }
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }


    public function deleteStaffInventory(int $staff_id, int $inventory_id)
    {
        DB::beginTransaction();
        try {
            $staff = Staff::find($staff_id);
            $inventory = Inventory::find($inventory_id);
            if (!$staff || !$inventory) {
                ResponseMessage('Staff or inventory not found');
            } else {
                $staff->inventories()->detach($inventory->id);
                DB::commit();
                ResponseMessage("Inventory detach successfully");
            }
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }


    public function deleteStaffFeature(int $staff_id, int $feature_id)
    {
        DB::beginTransaction();
        try {
            $staff = Staff::find($staff_id);
            $feature = Feature::find($feature_id);
            if (!$staff || !$feature) {
                ResponseMessage('Staff or feature not found');
            } else {
                $staff->features()->detach($feature->id);
                DB::commit();
                ResponseMessage("Detach successfully");
            }
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
