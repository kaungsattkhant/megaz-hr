<?php

namespace App\Repositories\Role;

use App\Http\Resources\Mobile\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $query = Role::orderBy('created_at', 'desc')->with('department', 'skills');

            if ($request->department_id) {
                $query->where('department_id', $request->department_id);
            }

            $paginatedRoles = $query->paginate(config('common.list_count'));
            return $paginatedRoles;
        } else {
            if ($request->department_id) {
                $roles = Role::where('department_id', $request->department_id)->with('department', 'skills')->get();
            } else {
                $roles = Role::with('department')->get();
            }
            return $roles;
        }
    }

    public function getRole($request){
        $data= Role::orderBy('level','asc')->get();
        return RoleResource::collection($data);
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
                $data = RemoveNullValues($data);
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
