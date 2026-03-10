<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;

use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleAPIController extends Controller
{
    //
    protected $roleRepo;
    public function __construct(RoleRepositoryInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    public function getRoleData(Request $request)
    {
        $roles = $this->roleRepo->listAllData($request);
        ResponseData($roles);
    }

    public function getOrganizationChart(Request $request)
    {
        $roles = $this->roleRepo->getOrganizationChart($request);
        ResponseData($roles);
    }

    public function createRole(RoleCreateRequest $request)
    {
        $role = $this->roleRepo->createData($request->all());
        ResponseData($role);
    }

    public function updateRole(RoleUpdateRequest $request, $id)
    {
        $role = $this->roleRepo->updateData($request->all(), $id);
        ResponseData($role);
    }

    public function getRoleByDepartment($department_id)
    {
        $data = $this->roleRepo->getRoleByDepartment($department_id);
        ResponseData($data);
    }

    public function getRoleByDepartments(Request $request)
    {
        $data = $this->roleRepo->getRoleByDepartments($request->all());
        ResponseData($data);
    }


    public function roleAvailableToggle($roleId)
    {
        $role = $this->roleRepo->roleAvailableToggle($roleId);
        ResponseData($role);
    }
}
