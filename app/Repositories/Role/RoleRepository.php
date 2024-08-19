<?php

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;

            if ($request->department_id) {
                $totalCount = Role::where('department_id', $request->department_id)->count();
                $roles = Role::where('department_id', $request->department_id)->skip($skip)->take($perPage)->with('department','skills')->get();
            } else {
                $totalCount = Role::count();
                $roles = Role::skip($skip)->take($perPage)->with('department','skills')->get();
            }

            $paginationData = MakePaginationData($request, $totalCount, 'roles');
            $paginationData['roles'] = $roles;

            return $paginationData;
        } else {
            if ($request->department_id) {
                $roles = Role::where('department_id', $request->department_id)->with('department','skills')->get();
            } else {
                $roles = Role::with('department','skills')->get();
            }
            return $roles;
        }
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
            $role = Role::find($id);
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

    public function getRoleByDepartment($department_id){
        return Role::where('department_id',$department_id)->get();
    }
}
