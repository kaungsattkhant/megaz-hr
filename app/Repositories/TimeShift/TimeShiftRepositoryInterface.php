<?php

namespace App\Repositories\TimeShift;

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

  public function deleteTimeShiftById($timeShiftId);
}
