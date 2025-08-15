<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\StaffTimeShift\StaffTimeShiftRepositoryInterface;
use Illuminate\Http\Request;

class StaffTimeShiftController extends Controller
{
    private StaffTimeShiftRepositoryInterface $staffTimeShiftRepository;
    public function __construct(StaffTimeShiftRepositoryInterface $staffTimeShiftRepository)
    {
        $this->staffTimeShiftRepository = $staffTimeShiftRepository;
    }

    public function getStaffTimeShifts(Request $request)
    {
        return $this->staffTimeShiftRepository->getStaffTimeShifts($request);
    }

    public function createStaffTimeShift(Request $request)
    {
        return $this->staffTimeShiftRepository->createStaffTimeShift($request->all());
    }

    public function updateStaffTimeShiftStatus($id ,Request $request)
    {
        return $this->staffTimeShiftRepository->updateStaffTimeShiftStatus($id, $request->all());
    }
}
