<?php

namespace App\Repositories\TimeShift;

use App\Models\Gps;
use App\Models\Shift;
use App\Models\TimeShift;

class TimeShiftRepository implements TimeShiftRepositoryInterface
{

  public function getGPS($request)
  {
    return Gps::all();
  }

  public function getGPSById(int $gpsId)
  {
    return Gps::find($gpsId);
  }

  public function updateGPSById($request, int $gpsId)
  {
    $gps = Gps::find($gpsId);
    $gps->update($request);
    return $gps;
  }

  public function getShifts($request)
  {
    return Shift::all();
  }

  public function getShiftsById(int $shiftId)
  {
    return Shift::find($shiftId);
  }

  public function storeShifts($data)
  {
    return Shift::create($data);
  }

  public function getTimeShift($request)
  {
    return TimeShift::with('shift')->get();
  }

  public function getTimeShiftById($timeShiftId)
  {
    return TimeShift::with('shift')->find($timeShiftId);
  }

  public function storeTimeShift($data)
  {
    return TimeShift::create($data);
  }

  public function updateTimeShift($data, $timeShiftId)
  {
    $timeShift = TimeShift::find($timeShiftId);
    $timeShift->update($data);
    return $timeShift;
  }

  public function deleteTimeShiftById($timeShiftId)
  {
    $timeShift = TimeShift::find($timeShiftId);
    $timeShift->delete();
    return $timeShift;
  }
}
