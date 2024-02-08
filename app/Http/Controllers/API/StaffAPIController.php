<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

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

    public function getStaffData(Request $request)
    {
        $staffs = $this->staffRepo->listAllData($request);
        ResponseData($staffs);
    }

    public function createStaff(StaffCreateRequest $request)
    {
        $data = $request->all();
        $data['roles'] = explode(',', $request->roles);
        $staff = $this->staffRepo->createData($data);

        ResponseData($staff);
    }

    public function updateStaff(StaffUpdateRequest $request, $id)
    {
        $staff = $this->staffRepo->updateData($request->all(), $id);
       if(!$staff)
       {
        ResponseMessage('Staff not found with given ID',404);
       }
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
