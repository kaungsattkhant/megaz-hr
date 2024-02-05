<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Department\DepartmentCreateRequest;
use App\Http\Requests\Department\DepartmentUpdateRequest;

use App\Repositories\Department\DepartmentRepositoryInterface;

class DepartmentAPIController extends Controller
{
    //
    protected $repoDepartment;
    public function __construct(DepartmentRepositoryInterface $repoDepartment)
    {
        $this->repoDepartment = $repoDepartment;
    }

    public function getDepartmentData()
    {
        $departments = $this->repoDepartment->listAllData();
        ResponseData($departments);
    }

    public function createDepartment(DepartmentCreateRequest $request)
    {
        $department = $this->repoDepartment->createData($request->all());
        ResponseData($department);
    }

    public function updateDepartment(DepartmentUpdateRequest $request, $id)
    {
        $department = $this->repoDepartment->updateData($request->all(), $id);
        ResponseData($department);
    }
}
