<?php

namespace App\Repositories\StaffTimeShift;

interface StaffTimeShiftRepositoryInterface
{
  public function getStaffTimeShifts($request);
  public function createStaffTimeShift(array $data);
  public function updateStaffTimeShiftStatus($id, array $data);
}
