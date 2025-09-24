<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Department\DepartmentCreateRequest;
use App\Http\Requests\Department\DepartmentUpdateRequest;

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

    public function getDepartmentData(Request $request)
    {
        $departments = $this->repoDepartment->listAllData($request);
        ResponseData($departments);
    }

    public function getDepartments()
    {
        $departments = $this->repoDepartment->getDepartments();
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
