<?php

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;

            if($request->department_id){
                $totalCount = Role::where('department_id', $request->department_id)->count();
                $roles = Role::where('department_id', $request->department_id)->skip($skip)->take($perPage)->with('department')->get();
            }
            else{
                $totalCount = Role::count();
                $roles = Role::skip($skip)->take($perPage)->with('department')->get();
            }

            $paginationData = MakePaginationData($request, $totalCount, 'roles');
            $paginationData['roles'] = $roles;

            return $paginationData;
        }
        else{
            if($request->department_id){
                $roles = Role::where('department_id', $request->department_id)->with('department')->get();
            }
            else{
                $roles = Role::with('department')->get();
            }
            return $roles;
        }
    }

    public function createData(array $data)
    {
        $role = Role::create($data);
        return $role;
    }

    public function updateData(array $data, int $id)
    {
        $role = Role::find($id);
        if($role)
        {
            $data = RemoveNullValues($data);
            $role->update($data);
        }
        return $role;
    }
}
