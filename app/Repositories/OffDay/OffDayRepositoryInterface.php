<?php

namespace App\Repositories\OffDay;

interface OffDayRepositoryInterface
{
  public function getOffDays();
  public function createOffDay($data);
  public function deleteOffDay($id);

  public function createPublicHoliday($data);
  public function toggleOffDaySetting($data);
}
