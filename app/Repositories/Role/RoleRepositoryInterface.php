<?php

namespace App\Repositories\Role;

use Illuminate\Http\Request;

interface RoleRepositoryInterface
{
    public function listAllData(Request $request);

    public function getOrganizationChart($request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function getRoleByDepartment($department_id);

    public function getRoleByDepartments(array $data);

    public function roleAvailableToggle($roleId);
}
