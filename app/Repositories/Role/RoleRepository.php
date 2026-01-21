<?php

namespace App\Repositories\Role;

use App\Models\Role;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Mobile\RoleResource;
use App\Http\Resources\Mobile\StaffOrganizationChartResource;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $query = Role::orderBy('created_at', 'desc')->with('department', 'skills', 'parent');

            if ($request->department_id) {
                $query->where('department_id', $request->department_id);
            }

            $paginatedRoles = $query->paginate(config('common.list_count'));
            return $paginatedRoles;
        } else {
            if ($request->department_id) {
                $roles = Role::where('department_id', $request->department_id)->with('department', 'skills', 'parent')
                    ->where('is_available', 1)
                    ->get();
            } else {
                $roles = Role::with('department', 'skills', 'parent')->where('is_available', 1)->get();
            }
            return $roles;
        }
    }

    public function getOrganizationChart($request)
    {
        // Load all active staffs with their roles and keep only those whose primary role is available
        $staffs = \App\Models\Staff::with('roles')
            ->where('is_active', 1)
            ->get()
            ->filter(fn($s) => ($s->primaryRole() && ($s->primaryRole()->is_available ?? 0) == 1))
            ->values();

        // init children container
        $staffs->each(fn($s) => $s->children = collect());

        // helper to return primary Role model or null
        $getRole = function ($staff) {
            return $staff->primaryRole();
        };

        // build tree by mapping staff under staffs whose role is the parent role
        $tree = collect();
        foreach ($staffs as $staff) {
            $role = $getRole($staff);
            if (!$role || !$role->parent_id) {
                $tree->push($staff);
                continue;
            }

            $parentRoleId = $role->parent_id;
            $parentStaffs = $staffs->filter(fn($s) => optional($getRole($s))->id === $parentRoleId);

            if ($parentStaffs->isEmpty()) {
                $tree->push($staff);
            } else {
                // attach child to first parent staff only to avoid duplicate placement
                $firstParent = $parentStaffs->first();
                if ($firstParent) {
                    $firstParent->children->push($staff);
                }
                // foreach ($parentStaffs as $parentStaff) {
                //     $parentStaff->children->push($staff);
                // }
            }
        }

        // sort nodes by role.level (fallback 0) recursively
        $sortRecursively = function ($nodes) use (&$sortRecursively, $getRole) {
            return $nodes->sortBy(fn($n) => optional($getRole($n))->level ?? 0)
                         ->values()
                         ->map(fn($n) => tap($n, fn($x) => $x->children = $sortRecursively($x->children)))
                         ->values();
        };

        $tree = $sortRecursively($tree);

        // format output to match sample
        $format = function ($nodes) use (&$format, $getRole) {
            return $nodes->map(function ($staff) use ($format, $getRole) {
                $role = $getRole($staff);
                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'role' => $role ? [
                        'id' => $role->id,
                        'name' => $role->name,
                        'level' => $role->level,
                    ] : null,
                    'children' => $format($staff->children),
                ];
            })->values();
        };

        return StaffOrganizationChartResource::collection($tree);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $role = Role::create($data);
            DB::commit();
            return $role;
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
            $role = Role::findOrFail($id);
            if ($role) {
                // $data = RemoveNullValues($data);
                $role->update($data);
            }
            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getRoleByDepartment($department_id)
    {
        return Role::where('department_id', $department_id)->with('skills')->get();
    }

    public function roleAvailableToggle($roleId)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($roleId);
            $role->is_available = $role->is_available ? 0 : 1;
            $role->save();
            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
