<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\StaffTimeShift\StaffTimeShiftRepositoryInterface;
use App\Repositories\OffDay\OffDayRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\OffDayRequest;
use App\Models\StaffTimeshift;

class StaffTimeShiftController extends Controller
{
    private StaffTimeShiftRepositoryInterface $staffTimeShiftRepository;
    private OffDayRepositoryInterface $offDayRepo;

    public function __construct(StaffTimeShiftRepositoryInterface $staffTimeShiftRepository, OffDayRepositoryInterface $offDayRepo)
    {
        $this->staffTimeShiftRepository = $staffTimeShiftRepository;
        $this->offDayRepo = $offDayRepo;
    }

    public function getStaffTimeShifts(Request $request)
    {
        $data= $this->staffTimeShiftRepository->getStaffTimeShifts($request);
        \ResponseMessage($data);
    }

    public function createStaffTimeShift(Request $request)
    {
        return $this->staffTimeShiftRepository->createStaffTimeShift($request->all());
    }

    public function updateStaffTimeShiftStatus($id ,Request $request)
    {
        return $this->staffTimeShiftRepository->updateStaffTimeShiftStatus($id, $request->all());
    }

    public function createOffDayRequest($id, Request $request)
    {
        $timeshift = StaffTimeshift::find($id);
        if(!$timeshift){
            ResponseMessage("No staff timeshift found with given ID", 404);
        }
        if($timeshift->status !== 'confirmed'){
            ResponseMessage('Staff time shift is not confirmed', 403);
        }

        $data = [
            'remark' => $request->remark?? null,
            'staff_timeshift_id' => $timeshift->id,
        ];

        $offDayReq = $this->offDayRepo->createOffDayRequest($data);
        if(!$offDayReq){
            ResponseMessage("Error requesting off day", 500);
        }
        ResponseData($offDayReq);
    }
}
