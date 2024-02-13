<?php

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allRoles = Role::with("department")->get();
        if($request->all_minified){
            return $allRoles;
        }
        $roles = Pagination($allRoles,$request,'roles');
        return $roles;
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
