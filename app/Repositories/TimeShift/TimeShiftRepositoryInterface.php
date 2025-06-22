<?php

namespace App\Repositories\TimeShift;

use Illuminate\Http\Request;

interface TimeShiftRepositoryInterface
{

  public function getGPS($request);

  public function getGPSById(int $gpsId);

  public function updateGPSById($request, int $gpsId);

  public function getShifts($request);

  public function getShiftsById(int $shiftId);

  public function storeShifts($data);

  public function getTimeShift($request);

  public function getTimeShiftById($timeShiftId);

  public function storeTimeShift($data);

  public function updateTimeShift($data, $timeShiftId);

  public function toggleTimeShift($timeShiftId);

  public function deleteTimeShiftById($timeShiftId);

  public function getCurrentTimeShift($request);

  public function checkIn(array $validatedData);

  public function checkOut(array $validatedData, $checkInId);

  public function getAllCheckIns(Request $request);

  public function getTotalHoursCheckIns(Request $request);
}
