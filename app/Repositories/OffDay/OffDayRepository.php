<?php

namespace App\Repositories\OffDay;

use Exception;
use App\Models\OffDay;
use App\Models\OffDayRequest;
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

  public function createOffDayRequest($data)
  {
    try{
        DB::beginTransaction();
        $offDayRequest = OffDayRequest::updateOrCreate([
            'staff_timeshift_id' => $data['staff_timeshift_id']
        ], $data);
        DB::commit();
        return $offDayRequest;
    }catch(Exception $e){
        DB::rollBack();
        return null;
    }
  }

  public function getOffDayRequests()
  {
    return OffDayRequest::with([
        'staffTimeshift.staff',
        'staffTimeshift.timeshift.shift',
        'staffTimeshift.timeshift.gps',
        'staffTimeshift.area',
        'handledBy'
    ])->orderByDesc('id')
    ->paginate(config('common.list_count'));
  }

  public function updateOffDayRequestStatus($id, $request)
  {
    $offDayRequest = OffDayRequest::with('staffTimeshift')->find($id);
    $staffTimeshift = $offDayRequest->staffTimeshift;

    if(!$offDayRequest || $offDayRequest->status !== 'pending'){
        ResponseMessage("This request doesn't exist or cannot be processed", 400);
    }

    $offDayRequest->status = $request->status;
    $offDayRequest->handled_by = UserData()->id;
    $offDayRequest->handled_at = now();
    $offDayRequest->save();

    if($offDayRequest->status == 'confirmed'){
        if($offDayRequest->type == 'off_day'){
            $offDay = OffDay::create(['repetition' => 'Custom', 'created_by' => UserData()->id]);
            DayInOffDay::create([
                'day' => 'Custom',
                'off_day_id' => $offDay->id,
                'date' => $staffTimeshift->date_time,
                'staff_id' => $staffTimeshift->staff_id,
            ]);
            $staffTimeshift->status = 'cancelled';
            $staffTimeshift->cancelled_by = UserData()->id;
            $staffTimeshift->cancelled_at = now();
            $staffTimeshift->save();
        }
        if($offDayRequest->type == 'shift_change'){
            $staffTimeshift->timeshift_id = $offDayRequest->change_timeshift_id;
            $staffTimeshift->save();
        }
    }
  }
}
