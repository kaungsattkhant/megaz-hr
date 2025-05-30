<?php

namespace App\Repositories\Staff;

use App\Models\Feature;
use App\Models\Inventory;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffAdvance;
use App\Models\StaffBalance;
use App\Models\StaffEmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffRepository implements StaffRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $departmentIds = $request->department_id;
        $roleIds = $request->role_id;
        $staffQuery = Staff::orderByDesc('id')->where('is_cv', 0)
            ->with(['department', 'roles'])
            ->when($request->search_input, function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search_input . '%');
            })
            ->when($departmentIds, function ($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            })
            ->when($roleIds, function ($query) use ($roleIds) {
                $query->whereHas('roles', function ($q) use ($roleIds) {
                    $q->whereIn('id', $roleIds);
                });
            })
            ->when(!isset($request->page), function ($q) {
                $q->where('is_active', 1);
            });
        $staff = isset($request->page) ? $staffQuery->paginate(config('common.list_count')) : $staffQuery->get();
        return $staff;
    }

    public function staffBalanceList(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $staffBalances = StaffBalance::with('staff')->where('month', $month)->where('year', $year)->orderBy('created_at', 'desc')->paginate(config('common.list_count'));

        foreach ($staffBalances as $staffBalance) {
            $totalAddition = StaffAdvance::where('staff_id', $staffBalance->staff_id)
                ->whereMonth('date_time', $month)
                ->whereYear('date_time', $year)
                ->where('type', 'addition')
                ->sum('amount') ?? 0;

            $totalSettlement = StaffAdvance::where('staff_id', $staffBalance->staff_id)
                ->whereMonth('date_time', $month)
                ->whereYear('date_time', $year)
                ->where('type', 'settlement')
                ->sum('amount') ?? 0;

            $staffBalance->addition = $totalAddition;
            $staffBalance->settlement = $totalSettlement;
            $staffBalance->staff_name = $staffBalance->staff->name;
            unset($staffBalance->staff);
        }

        ResponseData($staffBalances);
    }

    public function staffBalanceDetail(Request $request, int $id)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $staff = Staff::with([
            'staffAdvances' => function ($query) use ($month, $year) {
                $query->whereMonth('date_time', $month)
                    ->whereYear('date_time', $year);
            },
            'staffBalance' => function ($query) use ($month, $year) {
                $query->where('month', $month)
                    ->where('year', $year);
            }
        ])->where('id', $id)->first();

        ResponseData($staff);
    }


    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['is_active'] = 1;
            $data = RemoveNullValues($data);
            $staff = Staff::create($data);

            if (isset($data['inventoryIds'])) {
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

            if (isset($data['featureIds'])) {
                $featureIds = json_decode($data['featureIds']);
                foreach ($featureIds as $featureId) {
                    $staff->features()->attach($featureId);
                }
            }

            if (isset($data['skills'])) {
                $skills = json_decode($data['skills']);
                foreach ($skills as $skill) {
                    $staff->skills()->attach($skill);
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
                if ($staff->department_id != $data['department_id']) {
                    $staff->roles()->detach();
                }
                if (isset($data['nrc_front_path'])) {
                    if ($staff->nrc_front_path) {
                        DeleteFileFromServer($staff->nrc_front_path);
                    }
                }

                if (isset($data['nrc_back_path'])) {
                    if ($staff->nrc_back_path) {
                        DeleteFileFromServer($staff->nrc_back_path);
                    }
                }

                if (isset($data['household_registration_path'])) {
                    if ($staff->household_registration_path) {
                        DeleteFileFromServer($staff->household_registration_path);
                    }
                }

                $staff->update($data);

                if (isset($data['roles']) && $data['roles'] !== null) {
                    $rolesToAttach = $data['roles'];
                    $staff->roles()->sync($rolesToAttach);
                }
                if (isset($data['inventoryIds']) && $data['inventoryIds'] !== null) {
                    $inventoryIds = json_decode($data['inventoryIds'], true);
                    $staff->inventories()->sync($inventoryIds);
                } else {
                    $staff->inventories()->detach();
                }

                if (isset($data['featureIds']) && $data['featureIds'] !== null) {
                    $featureIds = json_decode($data['featureIds'], true);
                    $staff->features()->sync($featureIds);
                }

                if (isset($data['skills']) && $data['skills'] !== null) {
                    $skills = json_decode($data['skills'], true);
                    $staff->skills()->sync($skills);
                }
            }
            DB::commit();
            return $staff;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function staffDetail(int $id)
    {
        $staff = Staff::with('department', 'roles', 'inventories', 'emergencyContacts', 'gender', 'completed_tasks', 'features', 'skills')->find($id);
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

    public function getStaffByDepartment(Request $request, int $departmentId, array $roles = null)
    {

        $allowedRoles = $roles ? (in_array('Manager', $roles)
            ? ['Supervisor', 'Staff']
            : ['Staff']) : null;
        if ($request->per_page || $request->page) {
            // $totalCount = Staff::where('department_id', $departmentId)->where('is_active', 1)->count();
            // $pageNumber = 1;
            // $perPage = 20;
            // if ($request->page) {
            //     $pageNumber = $request->page;
            // }
            // if ($request->per_page) {
            //     $perPage = $request->per_page;
            // }
            // $skip = ($pageNumber - 1) * $perPage;
            // $staffs = Staff::with('department')->where('department_id', $departmentId)
            //     ->where('is_active', 1)
            //     ->skip($skip)->take($perPage)
            //     ->get();
            // $staffData = MakePaginationData($request, $totalCount, 'staffs', $staffs);

            $staffs = Staff::with(['department', 'roles'])
                ->when($roles, function ($query) use ($allowedRoles) {
                    $query->whereHas('roles', function ($query) use ($allowedRoles) {
                        $query->whereIn('name', $allowedRoles);
                    });
                })
                ->where('department_id', $departmentId)
                ->where('is_active', 1)
                ->paginate(20);
            return $staffs;
        } else {
            $staffs = Staff::with(['department', 'roles'])
                ->when($roles, function ($query) use ($allowedRoles) {
                    $query->whereHas('roles', function ($query) use ($allowedRoles) {
                        $query->whereIn('name', $allowedRoles);
                    });
                })
                // ->whereHas('roles', function ($query) use ($allowedRoles) {
                //     $query->whereIn('name', $allowedRoles);
                // })
                ->where('department_id', $departmentId)
                ->where('is_active', 1)
                ->get();

            return $staffs;
        }
    }

    public function getStaffByDepartmentSlug($slug)
    {
        $staffs = Staff::with('department')
            ->whereHas('department', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('is_active', 1)
            ->get();
        return $staffs;
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

    public function staffReport(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $subQuery = DB::table('task_details')
            ->join('tasks', 'task_details.task_id', '=', 'tasks.id')
            ->where('task_details.status', 'passed')
            ->when($month, function ($query) use ($month, $year) {
                $query->whereYear('task_details.date_time', $year)
                    ->whereMonth('task_details.date_time', $month);
            })
            ->select('task_details.staff_id', DB::raw('SUM(tasks.kpi) as kpi'))
            ->groupBy('task_details.staff_id');

        $query = Staff::leftJoinSub($subQuery, 'kpi', function ($join) {
            $join->on('staff.id', '=', 'kpi.staff_id');
        })
            ->select('staff.*', 'kpi.kpi')
            ->where(function ($query) {
                $query->whereNotNull('kpi.kpi')
                    ->where('kpi.kpi', '>', 0);
            });

        if ($request->filled('department_id')) {
            $query->where('staff.department_id', $request->input('department_id'));
        }

        $staffs = $query->orderBy('created_at', 'desc')->paginate(config('common.list_count'));

        ResponseData($staffs);
    }

    public function staffDuty(Request $request, int $id)
    {
        $date = $request->query('date');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $fromDate = $fromDate ? \Carbon\Carbon::parse($fromDate)->startOfDay() : null;
        $toDate = $toDate ? \Carbon\Carbon::parse($toDate)->endOfDay() : null;

        $staff = Staff::with([
            'duties' => function ($query) use ($date, $fromDate, $toDate) {
                if ($date) {
                    $query->whereDate('date', $date);
                } elseif ($fromDate && $toDate) {
                    $query->whereBetween('date', [$fromDate, $toDate]);
                } else {
                    $query->whereDate('date', CurrentDate());
                }
                $query->with('cookingPlace', 'tasks');
            }
        ])->find($id);

        ResponseData($staff);
    }
}
