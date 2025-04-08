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
}
