<?php

namespace App\Repositories\Role;

use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function listAllData()
    {
        $roles = Role::all();
        return $roles;
    }

    public function createData(array $data)
    {
        $role = Role::create($data);
        return $role;
    }

    public function updateData(array $data, string $id)
    {
        if ($id) {
            $role = Role::find($id);
            $role->update($data);
        } else {
            $role = Role::create($data);
        }
        return $role;
    }
}
