<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\StaffTimeShift\StaffTimeShiftRepositoryInterface;
use App\Repositories\OffDay\OffDayRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\OffDayRequest;
use App\Models\StaffTimeshift;

use App\Enums\OffDayRequestType;

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

        $request->validate([
            'type' => [
                'required',
                \Illuminate\Validation\Rule::enum(OffDayRequestType::class)
            ],
            'change_timeshift_id' => 'required_if:type,shift_change|exists:time_shifts,id',
        ]);

        $timeshift = StaffTimeshift::find($id);
        if(!$timeshift){
            ResponseMessage("No staff timeshift found with given ID", 404);
        }
        if($timeshift->status !== 'confirmed'){
            ResponseMessage('Staff time shift is not confirmed', 403);
        }

        $data = [
            'type' => $request->type,
            'remark' => $request->remark?? null,
            'staff_timeshift_id' => $timeshift->id,
        ];

        if($data['type'] === 'shift_change'){
            if($timeshift->timeshift_id == $request->change_timeshift_id){
                ResponseMessage("Change shift and original shift cannot be the same");
            }
            $data['original_timeshift_id'] = $timeshift->timeshift_id;
            $data['change_timeshift_id'] = (int) $request->change_timeshift_id;
        }

        // dd($data);

        $offDayReq = $this->offDayRepo->createOffDayRequest($data);
        if(!$offDayReq){
            ResponseMessage("Error requesting off day", 500);
        }
        ResponseData($offDayReq);
    }
}
