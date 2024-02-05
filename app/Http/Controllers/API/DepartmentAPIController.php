<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentCreateRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Http\Request;

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

    public function updateDepartment(DepartmentUpdateRequest $request, string $id)
    {
        $department = $this->repoDepartment->updateData($request->all(), $id);
        ResponseData($department);
    }
}
