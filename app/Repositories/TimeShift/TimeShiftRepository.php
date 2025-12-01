<?php

namespace App\Repositories\TimeShift;

use Exception;

use Carbon\Carbon;
use App\Models\Gps;
use App\Models\Shift;
use App\Models\CheckIn;
use App\Models\TimeShift;
use Illuminate\Http\Request;
use App\Models\StaffTimeshift;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CheckInResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\mobileCheckInResource;
use App\Http\Resources\GetCurrentTimeShiftResource;
use App\Http\Resources\Timeshift\StaffTimeShiftByStaffResource;

class TimeShiftRepository implements TimeShiftRepositoryInterface
{

  public function getShifts($request)
  {
    return Shift::orderBy('id', 'desc')->get();
  }

  public function getShiftsById(int $shiftId)
  {
    return Shift::find($shiftId);
  }

  public function storeShifts($data)
  {
    return Shift::updateOrCreate(
      ['id' => $data['id'] ?? null],
      $data
    );
  }

  public function getTimeShift($request)
  {
    return TimeShift::with(['gps', 'shift'])->orderBy('id', 'desc')->get();
  }

  public function getTimeShiftById($timeShiftId)
  {
    return TimeShift::with(['gps', 'shift'])->find($timeShiftId);
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
  public function toggleTimeShift($timeShiftId)
  {
    $timeShift = TimeShift::find($timeShiftId);
    $timeShift->update([
      'is_active' => !$timeShift->is_active
    ]);
    return $timeShift;
  }

  public function deleteTimeShiftById($timeShiftId)
  {
    $timeShift = TimeShift::find($timeShiftId);
    $timeShift->delete();
    return $timeShift;
  }

  public function getCurrentTimeShift($request)
  {
    // $gps = Gps::first();
    if (!isset($request->staff_id) || $request->staff_id == null) {
      ResponseMessage('Staff ID is required', 419);
    }
    $staffId = $request->input('staff_id');

    $today = Carbon::today();

    // $existShiftAssign = StaffTimeshift::join('time_shifts', function ($join) {
    //   $now = now();
    //   $graceMinutes = 30; // allow 30 mins early check-in
    //   $nowTime = $now->format('H:i');
    //   $graceTime = $now->copy()->addMinutes($graceMinutes)->format('H:i');
    //   $join->on('staff_timeshifts.timeshift_id', '=', 'time_shifts.id')
    //     ->where(function ($q) use ($nowTime, $graceTime) {
    //       $q->where(function ($q1) use ($nowTime, $graceTime): void {
    //         $q1->where('time_shifts.from_time', '<=', $nowTime)
    //           ->where('time_shifts.to_time', '>=', $nowTime);
    //       })
    //         ->orWhere(function ($q1) use ($nowTime) {
    //           $q1->where('time_shifts.from_time', '>', 'time_shifts.to_time')
    //             ->where(function ($q2) use ($nowTime) {
    //               $q2->where('time_shifts.from_time', '<=', $nowTime)
    //                 ->orWhere('time_shifts.to_time', '>=', $nowTime);
    //             });
    //         });
    //     })
    //     ->where('time_shifts.is_active', 1);
    // })
    //   ->where("staff_id", $staffId)
    //   ->whereHas('timeshift', function ($q) {
    //     $q->where('is_active', 1);
    //   })
    //   ->whereDate('date_time', $today)
    //   ->first();
    $existShiftAssign = StaffTimeshift::select('staff_timeshifts.*')
      ->join('time_shifts', function ($join) {
        $now = now()->format('H:i');
        $earlyCheckMinutes = 60; // allow 60 mins early check-in

        $join->on('staff_timeshifts.timeshift_id', '=', 'time_shifts.id')
          ->where(function ($q) use ($now, $earlyCheckMinutes) {
            // Normal shift: from_time < to_time
            $q->where(function ($q1) use ($now, $earlyCheckMinutes) {
              $q1->whereRaw("
                    TIME(?) >= SUBTIME(time_shifts.from_time, SEC_TO_TIME(? * 60))
                    AND TIME(?) <= time_shifts.to_time
                ", [$now, $earlyCheckMinutes, $now]);
            })
              // Overnight shift: from_time > to_time
              ->orWhere(function ($q1) use ($now, $earlyCheckMinutes) {
                $q1->where('time_shifts.from_time', '>', 'time_shifts.to_time')
                  ->where(function ($q2) use ($now, $earlyCheckMinutes) {
                    $q2->whereRaw("
                            TIME(?) >= SUBTIME(time_shifts.from_time, SEC_TO_TIME(? * 60))
                            AND TIME(?) <= '23:59:59'
                        ", [$now, $earlyCheckMinutes, $now])
                      ->orWhereRaw("
                            TIME(?) >= '00:00:00' AND TIME(?) <= time_shifts.to_time
                        ", [$now, $now]);
                  });
              });
          })
          ->where('time_shifts.is_active', 1);
      })
      ->where('staff_id', $staffId)
      ->where('status', 'confirmed')
      ->whereHas('timeshift', fn($q) => $q->where('is_active', 1))
      ->whereDate('date_time', today())
      ->first();
    // dd($existShiftAssign);
    if (!$existShiftAssign) {
      ResponseMessage('Check-in is invalid, you do not have any assigned shift', 419);
    }
    if ($existShiftAssign) {
      if ($existShiftAssign->status === 'pending') {
        ResponseMessage('Check-in is invalid ,you have to accept  shift assignment', 419);
      }
      if ($existShiftAssign->status === 'cancelled') {
        ResponseMessage('Check-in is invalid ,your shift assignment has been cancelled', 419);
      }
    }
    $gps = optional(optional($existShiftAssign)->timeshift)->gps;
    // dd($officeGps);
    if (!$gps) {
      ResponseData('Office GPS coordinates not found.', 422);
    }
    $currentTimeShift = $existShiftAssign->timeshift;

    $response = [
      'gps' => $gps,
      'current_time_shift' => $currentTimeShift ? new GetCurrentTimeShiftResource($currentTimeShift) : null,
      'staff_timeshif_id' => $existShiftAssign->id,
    ];

    $staffId = $request->input('staff_id');
    if (isset($staffId)) {

      if ($currentTimeShift) {
        $checkIn = CheckIn::where('staff_id', $staffId)
          ->where('time_shift_id', $currentTimeShift->id)
          ->orderBy('check_in_date_time', 'desc')
          ->first();
        //if checkin not exist,need to checkin for this timeshift
        //if checkin exist,need to checkout for this timeshift
        //if user is once checkin out for this timeshift in a day,is_current_checked_in will be false and already checked in condition will be true
        if (!$checkIn) {
          $response['check_in_status'] = 'check_in';
        } elseif (!$checkIn->is_current_checked_in && !is_null($checkIn->is_self_checkout)) {
          $response['check_in_status'] = 'already_checked_in';
          $response['check_in'] = new mobileCheckInResource($checkIn);
        } else {
          $response['check_in_status'] = 'check_out';
          $response['check_in'] = new mobileCheckInResource($checkIn);
        }
      } else {
        $response['check_in_status'] = 'check_in';
      }
    }
    return $response;
  }

  public function getAllCheckIns(Request $request)
  {
    $from_date = $request->input('from_date');
    $to_date = $request->input('to_date');
    $staff_id = $request->input('staff_id');
    $query = CheckIn::orderBy('id', 'desc')->with(['staff', 'timeShift.shift'])->checkInFilter($from_date, $to_date, $staff_id);

    $checkIns = $query->paginate(config('common.list_count'));
    return CheckInResource::collection($checkIns);
  }

  public function getTotalHoursCheckIns(Request $request)
  {
    $query = CheckIn::select(
      'check_ins.staff_id',
      'staff.name as staff_name',
      'check_ins.time_shift_id',
      DB::raw('SUM(TIMESTAMPDIFF(MINUTE, check_in_date_time, check_out_date_time)) AS total_minutes'),
      'time_shifts.shift_id',
      'shifts.name AS shift_name'
    )
      ->join('staff', 'check_ins.staff_id', '=', 'staff.id')
      ->join('time_shifts', 'check_ins.time_shift_id', '=', 'time_shifts.id')
      ->join('shifts', 'time_shifts.shift_id', '=', 'shifts.id')
      ->whereNotNull('check_out_date_time');


    if ($request->has(['from_date', 'to_date'])) {
      $query->dateFilter($request->input('from_date'), $request->input('to_date'));
    }

    if ($request->has('staff_id')) {
      $query->staffFilter($request->input('staff_id'));
    }
    if ($request->has('staff_name')) {
      $query->staffNameFilter($request->input('staff_name'));
    }
    if ($request->has('shift_id')) {
      $query->shiftFilter($request->input('shift_id'));
    }
    $checkIns = $query->groupBy(
      'check_ins.staff_id',
      'staff.name',
      'check_ins.time_shift_id',
      'time_shifts.shift_id',
      'shifts.name'
    )->paginate();

    $results = $checkIns->map(function ($data) {
      $hours = intdiv($data->total_minutes, 60);
      $minutes = $data->total_minutes % 60;
      return [
        'staff_id' => $data->staff_id,
        'staff_name' => $data->staff_name,
        'time_shift_id' => $data->time_shift_id,
        'shift_id' => $data->shift_id,
        'shift_name' => $data->shift_name,
        'total_work_hours' => sprintf('%d hours %d minutes', $hours, $minutes),
      ];
    });

    return $results;
  }

  public function adminPostedcheckIn(array $requestData)
  {
    
    try {
      if (!isset($requestData['id'])) {
        $requestData['id'] = null;
      }
      // dd($requestData);
      if(isset($requestData['id']) && !empty($requestData['id'])){
        self::validateCheckInEdit($requestData['time_shift_id'], $requestData['check_in_date_time']);
      }
      $requestData['is_current_checked_in']=true;
      DB::beginTransaction();
      $checkIn = CheckIn::updateOrCreate([
        'id'=>$requestData['id']
      ],
        $requestData
      );
      DB::commit();
      ResponseData($checkIn);
    } catch (Exception $e) {
      DB::rollBack();
      ResponseMessage("Admin check-in posting failed: {$e->getMessage()}", 500);
    }
  }
  public function getStaffTimeShfitByStaff($staffId){
    $currentTime = now()->format('H:i'); 
    $existShiftAssigns = StaffTimeshift::with('timeshift.shift')->where('staff_id', $staffId)
      ->where('status', 'confirmed')
      // ->whereHas('timeshift', fn($q) => $q->where('is_active', 1))
      ->whereDate('date_time', today())
      ->whereHas('timeshift',function($q)use($currentTime){
        // $q->where('from_time','>',$currentTime)
        $q->where('is_active', 1);
      })
      ->get();
      return StaffTimeShiftByStaffResource::collection($existShiftAssigns);
  }

  public function validateCheckInEdit($timeShiftId, $checkInDateTime){
    $timeShift = TimeShift::find($timeShiftId);
    // $checkInDate=Carbon::parse($checkInDateTime)->format('Y-m-d');
    // $compareDateTime = Carbon::parse($checkInDate.' '.$timeShift->from_time)->format('Y-m-d h:i');
    $checkIn = Carbon::parse($checkInDateTime); // e.g., "2025-11-29 13:00"
    $compareDateTime = Carbon::parse(Carbon::parse($checkInDateTime)->format('Y-m-d') . ' ' . $timeShift->from_time);
    $checkInPlus30 = $checkIn->copy()->addMinutes(30);
    // Compare
    if ($checkInPlus30->lessThanOrEqualTo($compareDateTime)) {
      return true;
    }

    \ResponseMessage("Permission doesn't allow to edit check-in",419);
  }

  public function checkIn(array $requestData)
  {
    DB::beginTransaction();

    try {
      $staffId = $requestData['staff_id'];
      $timeShiftId = $requestData['time_shift_id'];
      $today = Carbon::today();
      $currentDate = now()->format('Y-m-d');

      $existingCheckIn = CheckIn::where('staff_id', $staffId)
        ->whereDate('check_in_date_time', $currentDate)
        ->where('is_current_checked_in', true)
        ->first();

      $existShiftAssign = StaffTimeshift::where("staff_id", $staffId)
        ->where('timeshift_id', $timeShiftId)
        ->whereDate('date_time', $today)
        ->first();
      if (!$existShiftAssign) {
        ResponseMessage('Check-in is invalid, you do not have any assigned shift', 419);
      }
      if ($existShiftAssign) {
        if ($existShiftAssign->status === 'pending') {
          ResponseMessage('Check-in is invalid ,you have to accept  shift assignment', 419);
        }
        if ($existShiftAssign->status === 'cancelled') {
          ResponseMessage('Check-in is invalid ,your shift assignment has been cancelled', 419);
        }
      }
      if ($existingCheckIn) {
        return ResponseData('You have already checked in today', 422);
      }
      $userLat = $requestData['latitude'];
      $userLng = $requestData['longitude'];

      // $officeGps = Gps::where('name', 'GPS Point')->first();
      $officeGps = $existShiftAssign?->timeshift?->gps;

      // dd($officeGps);
      if (!$officeGps) {
        ResponseData('Office GPS coordinates not found.', 422);
      }
      $officeLat = $officeGps->latitude;
      $officeLng = $officeGps->longitude;

      $distance = $this->haversineFormula($userLat, $userLng, $officeLat, $officeLng);
      if ($distance > 500) {
        return ResponseData($data = null, $status_code = 422, false, $extra_message = "You are not within the allowed range.");
      }

      if (isset($requestData['check_in_photo'])) {
        $image = $requestData['check_in_photo'];
        $extension = $image->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $imagePath = $image->storeAs('staffImages', $hashedName, 'public');
        $imageUrl = Storage::url($imagePath);
      }

      $checkIn = CheckIn::create([
        'staff_id' => $requestData['staff_id'],
        'time_shift_id' => $requestData['time_shift_id'],
        'staff_timeshift_id' => $requestData['staff_timeshift_id'] ?? null,
        'check_in_date_time' => now(),
        'check_in_photo_path' => $imagePath ?? null,
        'check_in_photo_url' => $imageUrl ?? null,
        'is_current_checked_in' => true,
      ]);

      DB::commit();
      return $checkIn;
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $extra_message = "An error occurred during check-in.");
    }
  }

  public function checkOut(array $validatedData, $checkInId)
  {
    DB::beginTransaction();

    try {
      $checkIn = CheckIn::find($checkInId);

      if ($checkIn && $checkIn->is_current_checked_in) {
        if (isset($validatedData['check_out_photo'])) {
          $image = $validatedData['check_out_photo'];
          $extension = $image->getClientOriginalExtension();
          $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
          $imagePath = $image->storeAs('staffImages', $hashedName, 'public');
          $imageUrl = Storage::url($imagePath);
        }

        $checkIn->update([
          'check_out_date_time' => now(),
          'check_out_photo_path' => $imagePath ?? null,
          'check_out_photo_url' => $imageUrl ?? null,
          'is_current_checked_in' => false,
          'is_self_checkout' => true
        ]);

        DB::commit();
        return $checkIn;
      }

      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $extra_message = "Check-in not found.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $extra_message = "An error occurred during check-out.");
    }
  }

  /**
   * Calculate distance between two GPS points in meters.
   */
  private function haversineFormula($lat1, $lon1, $lat2, $lon2)
  {
    $earthRadius = 6371000; // in meters
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $latDelta = $lat2 - $lat1;
    $lonDelta = $lon2 - $lon1;

    $a = pow(sin($latDelta / 2), 2) +
      cos($lat1) * cos($lat2) * pow(sin($lonDelta / 2), 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
  }
}
