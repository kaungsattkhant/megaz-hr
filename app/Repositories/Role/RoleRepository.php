<?php

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Role::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $roles = Role::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'roles');
            $paginationData['roles'] = $roles;

            return $paginationData;
        }
        else{
            $roles = Role::all();

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
