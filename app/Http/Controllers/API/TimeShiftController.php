<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeShift\ShiftRequest;
use App\Http\Requests\TimeShift\CheckInRequest;
use App\Http\Requests\TimeShift\CheckOutRequest;
use App\Http\Requests\TimeShift\TimeShiftRequest;
use App\Repositories\TimeShift\TimeShiftRepositoryInterface;

class TimeShiftController extends Controller
{
    private TimeShiftRepositoryInterface $TimeShiftRepository;

    public function __construct(TimeShiftRepositoryInterface $TimeShiftRepository)
    {
        $this->TimeShiftRepository = $TimeShiftRepository;
    }

    public function getGPS(Request $request)
    {
        $data =  $this->TimeShiftRepository->getGPS($request->all());
        ResponseData($data);
    }

    public function getGPSById(int $gpsId)
    {
        $data =  $this->TimeShiftRepository->getGPSById($gpsId);
        ResponseData($data);
    }

    public function updateGPSById(Request $request, int $gpsId)
    {
        $data =  $this->TimeShiftRepository->updateGPSById($request->all(), $gpsId);
        ResponseData($data);
    }

    public function getShifts(Request $request)
    {
        $data =  $this->TimeShiftRepository->getShifts($request->all());
        ResponseData($data);
    }

    public function getShiftsById(int $shiftId)
    {
        $data =  $this->TimeShiftRepository->getShiftsById($shiftId);
        ResponseData($data);
    }

    public function storeShifts(ShiftRequest $request)
    {
        $data = $this->TimeShiftRepository->storeShifts($request->all());
        ResponseData($data);
    }

    public function getTimeShift(Request $request)
    {
        $data =  $this->TimeShiftRepository->getTimeShift($request->all());
        ResponseData($data);
    }

    public function getTimeShiftById($timeShiftId)
    {
        $data =  $this->TimeShiftRepository->getTimeShiftById($timeShiftId);
        ResponseData($data);
    }

    public function storeTimeShift(TimeShiftRequest $request)
    {
        $data = $this->TimeShiftRepository->storeTimeShift($request->all());
        ResponseData($data);
    }

    public function updateTimeShift(TimeShiftRequest $request, $timeShiftId)
    {
        $data = $this->TimeShiftRepository->updateTimeShift($request->all(), $timeShiftId);
        ResponseData($data);
    }

    public function toggleTimeShift($timeShiftId)
    {
        $data = $this->TimeShiftRepository->toggleTimeShift($timeShiftId);
        ResponseData($data);
    }

    public function deleteTimeShiftById($timeShiftId)
    {
        $data = $this->TimeShiftRepository->deleteTimeShiftById($timeShiftId);
        ResponseData($data);
    }

    public function getCurrentTimeShift(Request $request)
    {
        $data =  $this->TimeShiftRepository->getCurrentTimeShift($request);
        ResponseData($data);
    }

    public function checkIn(CheckInRequest $request)
    {
        $data =  $this->TimeShiftRepository->checkIn($request->all());
        ResponseData($data);
    }

    public function checkOut(CheckOutRequest $request, int $checkInId)
    {
        $data =  $this->TimeShiftRepository->checkOut($request->all(), $checkInId);
        ResponseData($data);
    }
    public function getAllCheckIns(Request $request)
    {
        $data =  $this->TimeShiftRepository->getAllCheckIns($request);
        return $data;
    }

    public function getTotalHoursCheckIns(Request $request)
    {
        $data =  $this->TimeShiftRepository->getTotalHoursCheckIns($request);
        ResponseData($data);
    }
}
