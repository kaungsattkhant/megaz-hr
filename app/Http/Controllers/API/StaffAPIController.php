<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Staff\StaffCreateRequest;
use App\Http\Requests\Staff\StaffUpdateRequest;

use App\Repositories\Staff\StaffRepositoryInterface;

class StaffAPIController extends Controller
{
    //
    protected $staffRepo;

    public function __construct(StaffRepositoryInterface $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }

    public function getStaffData()
    {
        $staffs = $this->staffRepo->listAllData();
        ResponseData($staffs);
    }

    public function createStaff(StaffCreateRequest $request)
    {
        $staff = $this->staffRepo->createData($request->all());
        ResponseData($staff);
    }

    public function updateStaff(StaffUpdateRequest $request, $id)
    {
        $staff = $this->staffRepo->updateData($request->all(), $id);
        ResponseData($staff);
    }

    public function deleteStaff($id)
    {
        $staffDeleted = $this->staffRepo->deleteData($id);
        if ($staffDeleted) {
            ResponseMessage("Staff deleted");
        } else {
            ResponseMessage('staff not found or some error occur');
        }
    }
}
