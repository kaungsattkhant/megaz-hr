<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Staff\StaffCreateRequest;
use App\Http\Requests\Staff\StaffUpdateRequest;

use App\Models\Staff;

use App\Repositories\Staff\StaffRepositoryInterface;
use Psy\Readline\Hoa\_Protocol;

class StaffAPIController extends Controller
{
    //
    protected $staffRepo;

    public function __construct(StaffRepositoryInterface $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }

    public function staffBalanceList(Request $request)
    {
        $staffBalances = $this->staffRepo->staffBalanceList($request);
    }

    public function detailStaffBalance(Request $request, int $id)
    {
        $staffBalance = $this->staffRepo->staffBalanceDetail($request, $id);
    }

    public function getStaffData(Request $request)

    {
        $staffs = $this->staffRepo->listAllData($request);
        ResponseData($staffs);
    }

    public function createStaff(Request $request)
    {
        $data = $request->except(['nrc_front_image', 'nrc_back_image', 'household_registration_image']);
        if ($request->hasFile('nrc_front_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_front_image', 'staff_images');
            $data['nrc_front_url'] = $uploadedFile['file_url'];
            $data['nrc_front_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('nrc_back_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_back_image', 'staff_images');
            $data['nrc_back_url'] = $uploadedFile['file_url'];
            $data['nrc_back_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('household_registration_image')) {
            $uploadedFile = UploadFileToServer($request, 'household_registration_image', 'staff_images');
            $data['household_registration_url'] = $uploadedFile['file_url'];
            $data['household_registration_path'] = $uploadedFile['file_path'];
        }
        $data['roles'] = explode(',', $request->roles);
        $staff = $this->staffRepo->createData($data);

        ResponseData($staff);
    }

    public function updateStaff(Request $request, $id)
    {
        $data = $request->except(['nrc_front_image', 'nrc_back_image', 'household_registration_image']);
        if ($request->hasFile('nrc_front_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_front_image', 'staff_images');
            $data['nrc_front_url'] = $uploadedFile['file_url'];
            $data['nrc_front_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('nrc_back_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_back_image', 'staff_images');
            $data['nrc_back_url'] = $uploadedFile['file_url'];
            $data['nrc_back_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('household_registration_image')) {
            $uploadedFile = UploadFileToServer($request, 'household_registration_image', 'staff_images');
            $data['household_registration_url'] = $uploadedFile['file_url'];
            $data['household_registration_path'] = $uploadedFile['file_path'];
        }
        $staff = $this->staffRepo->updateData($data, $id);

        if (!$staff) {
            ResponseMessage('Staff not found with given ID', 404);
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

    public function getStaffListBySupervisor(Request $request)
    {
        $staff = Staff::find($request->user()->id);

        $roles = $staff->roles->pluck('name')->toArray();
        $isSupervisorOrManager = in_array('Supervisor', $roles) || in_array('Manager', $roles);
        if (!$isSupervisorOrManager) {
            ResponseMessage('Not authorized', 403);
        }
        // $isASupervisor = false;
        // foreach ($roles as $role) {
        //     if ($role->name == 'Supervisor') {
        //         $isASupervisor = true;
        //         break;
        //     }
        // }
        // if (!$isASupervisor) {
        //     ResponseMessage('Not a supervisor', 403);
        // }

        $staff = $this->staffRepo->getStaffByDepartment($request, $staff->department_id, $roles);
        ResponseData($staff);
    }

    public function detailStaff(int $id)
    {
        $staff = $this->staffRepo->staffDetail($id);
        ResponseData($staff);
    }

    public function deleteRoleStaff(int $staff_id, int $role_id)
    {
        $staff = $this->staffRepo->deleteStaffRole($staff_id, $role_id);
    }

    public function deleteInventoryStaff(int $staff_id, int $inventory_id)
    {
        $staff = $this->staffRepo->deleteStaffInventory($staff_id, $inventory_id);
    }


    public function deleteFeatureStaff(int $staff_id, int $feature_id)
    {
        $staff = $this->staffRepo->deleteStaffFeature($staff_id, $feature_id);
    }

    public function getStaffByDepartment(Request $request, int $department_id)
    {
        $staff = $this->staffRepo->getStaffByDepartment($request, $department_id);
        ResponseData($staff);
    }

    public function getStaffByDepartmentSlug($slug)
    {
        $staff = $this->staffRepo->getStaffByDepartmentSlug($slug);
        ResponseData($staff);
    }

    public function staffReport(Request $request)
    {
        $staff = $this->staffRepo->staffReport($request);
    }

    public function getStaffWithDuties(Request $request, int $id)
    {
        $this->staffRepo->staffDuty($request, $id);
    }
}
