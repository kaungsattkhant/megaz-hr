<?php

namespace App\Repositories\OffDay;

use Exception;
use App\Models\OffDay;
use App\Models\DayInOffDay;
use App\Models\OffDayAssignment;
use App\Models\OffDaySetting;
use App\Models\PublicHoliday;
use Illuminate\Support\Facades\DB;
use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

class OffDayRepository implements OffDayRepositoryInterface
{
  public function getOffDays()
  {
    return DayInOffDay::with('offDay.OffDayAssignments.offdayable')->orderByDesc('id')
      ->paginate(config('common.list_count'));
  }

  public function createOffDay($validatedData)
  {
    DB::beginTransaction();
    try {
      $offDay = OffDay::create(
        [
          'repetition' => $validatedData['repetition'],
          'created_by' => UserData()->id,
        ]
      );
      if (isset($validatedData['days'])) {
        $days = json_decode($validatedData['days'], true);
        foreach ($days as $day) {
          DayInOffDay::create([
            'day' => $day,
            'off_day_id' => $offDay->id
          ]);
        }
      }

      if (isset($validatedData['offdayable_id'])) {
        $dayoffdayableIds = json_decode($validatedData['offdayable_id'], true);
        foreach ($dayoffdayableIds as $dayoffdayableId) {
          OffDayAssignment::create([
            'off_day_id' => $offDay->id,
            'offdayable_id' => $dayoffdayableId,
            'offdayable_type' => $validatedData['offdayable_type']
          ]);
        }
      }

      DB::commit();
      ResponseData($offDay);
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function deleteOffDay($dayInOffDayId)
  {
    DB::beginTransaction();
    try {
      $dayInOffDay = DayInOffDay::find($dayInOffDayId);
      if ($dayInOffDay) {
        $dayInOffDay->delete();
      }
      DB::commit();
      ResponseData($dayInOffDay);
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function createPublicHoliday($data)
  {
    DB::beginTransaction();
    try {
      $dates = json_decode($data['date'], true);
      $offDay = OffDay::create(
        [
          'repetition' => "Default",
          'created_by' => UserData()->id,
        ]
      );

      foreach ($dates as $date) {
        DayInOffDay::create([
          'day' => "Public-holiday",
          'off_day_id' => $offDay->id,
          'name' =>  $data['name'],
          'date' => $date
        ]);
      }
      DB::commit();
      ResponseData($offDay);
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function toggleOffDaySetting($data){
    DB::beginTransaction();
    try {
     $offDaySetting= OffDaySetting::find($data['id']);
     $offDaySetting->type=$data['type'];
     $offDaySetting->save();
      DB::commit();
      ResponseData($offDaySetting);
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
