<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;

use App\Repositories\Role\RoleRepositoryInterface;

class RoleAPIController extends Controller
{
    //
    protected $roleRepo;
    public function __construct(RoleRepositoryInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    public function getRoleData()
    {
        $roles = $this->roleRepo->listAllData();
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
}
