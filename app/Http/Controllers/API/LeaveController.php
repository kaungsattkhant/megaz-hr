<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreLeaveAllowanceRequest;
use App\Repositories\Leave\LeaveRepositoryInterface;

class LeaveController extends Controller
{
    private LeaveRepositoryInterface $leaveRepository;
    public function __construct(LeaveRepositoryInterface $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function getLeaveCategoryLists(Request $request)
    {
        $data = $this->leaveRepository->getLeaveCategoryLists($request);
        ResponseData($data);
    }
    public function createLeaveCategory(Request $request)
    {
        $data = $this->leaveRepository->createLeaveCategory($request->all());
        ResponseData($data);
    }

    public function createLeaveAllowance(Request $request)
    {
        $data = $this->leaveRepository->createLeaveAllowance($request->all());
        ResponseData($data);
    }

    public function getLeaveAllowance(Request $request)
    {
        $data = $this->leaveRepository->getLeaveAllowance($request);
        ResponseData($data);
    }
    public function deleteLeaveAllowance($id)
    {
        $data = $this->leaveRepository->deleteLeaveAllowance($id);
        ResponseData($data);
    }
    public function createLeave(Request $request)
    {
        $data = $this->leaveRepository->createLeave($request->all());
        ResponseData($data);
    }
    public function getLeave(Request $request)
    {
        $data = $this->leaveRepository->getLeave($request);
        ResponseData($data);
    }
    public function updateLeave(Request $request, $id)
    {
        $data = $this->leaveRepository->updateLeave($request->all(), $id);
        ResponseData($data);
    }
    public function deleteLeave($id)
    {
        $data = $this->leaveRepository->deleteLeave($id);
        ResponseData($data);
    }

    public function getLeaveTotalByStaff($staffId)
    {
        $data = $this->leaveRepository->getLeaveTotalByStaff($staffId);
        ResponseData($data);
    }

    public function getExitCategoryLists(Request $request)
    {
        $data = $this->leaveRepository->getExitCategoryLists($request);
        ResponseData($data);
    }

    public function createExitCategory(Request $request)
    {
        $data = $this->leaveRepository->createExitCategory($request->all());
        ResponseData($data);
    }

    public function createExitPass(Request $request)
    {
        $data = $this->leaveRepository->createExitPass($request->all());
        ResponseData($data);
    }

    public function getExitPass(Request $request)
    {
        $data = $this->leaveRepository->getExitPass($request);
        ResponseData($data);
    }

    public function updateExitPass(Request $request, $id)
    {
        $data = $this->leaveRepository->updateExitPass($request->all(), $id);
        ResponseData($data);
    }

    public function deleteExitPass($id)
    {
        $data = $this->leaveRepository->deleteExitPass($id);
        ResponseData($data);
    }

    public function getExitPassByStaff($staffId)
    {
        $data = $this->leaveRepository->getExitPassByStaff($staffId);
        ResponseData($data);
    }

    public function getStaffListByRoleAndDepartment($roleId, $departmentId)
    {
        $data = $this->leaveRepository->getStaffListByRoleAndDepartment($roleId, $departmentId);
        ResponseData($data);
    }
}
