<?php

namespace App\Repositories\TimeShift;

use App\Models\Gps;
use App\Models\Shift;
use App\Models\CheckIn;
use App\Models\TimeShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CheckInResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\GetCurrentTimeShiftResource;

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

  public function getCurrentTimeShift($request)
  {
    $gps = Gps::first();
    $currentTimeShift = TimeShift::with(['shift'])
      ->where('from_time', '<=', now()->format('H:i'))
      ->where('to_time', '>=', now()->format('H:i'))
      ->first();

    $response = [
      'gps' => $gps,
      'current_time_shift' => new GetCurrentTimeShiftResource($currentTimeShift),
    ];


    if (isset($request['staff_id'])) {
      $checkIn = CheckIn::where('staff_id', $request['staff_id'])
        ->where('time_shift_id', $currentTimeShift->id)
        ->orderBy('check_in_date_time', 'desc')
        ->first();

      if ($checkIn) {
        if ($checkIn->is_current_checked_in) {
          $response['check_in_status'] = 'checked_in';
        } else {
          $response['check_in_status'] = 'checked_out';
        }
      } else {
        $response['check_in_status'] = 'not_checked_in';
      }
    }

    return $response;
  }

  public function getAllCheckIns(Request $request)
  {
    $from_date = $request->input('from_date');
    $to_date = $request->input('to_date');
    $staff_id = $request->input('staff_id');

    $query = CheckIn::with(['staff', 'timeShift.shift'])->checkInFilter($from_date, $to_date, $staff_id);

    $checkIns = $query->paginate();
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
      'time_shifts.from_time',
      'time_shifts.to_time',
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
      'time_shifts.from_time',
      'time_shifts.to_time',
      'shifts.name'
    )->get();

    $results = $checkIns->map(function ($data) {
      $hours = intdiv($data->total_minutes, 60);
      $minutes = $data->total_minutes % 60;
      return [
        'staff_id' => $data->staff_id,
        'staff_name' => $data->staff_name,
        'time_shift_id' => $data->time_shift_id,
        'shift_id' => $data->shift_id,
        'from_time' => $data->from_time,
        'to_time' => $data->to_time,
        'shift_name' => $data->shift_name,
        // 'total_minutes' => $data->total_minutes,
        // 'total_hours' => $hours,
        // 'total_minutes' => $minutes,
        'total_work_hours' => sprintf('%d hours %d minutes', $hours, $minutes),
      ];
    });

    return $results;
  }


  public function checkIn(array $requestData)
  {

    $userLat = $requestData['latitude'];
    $userLng = $requestData['longitude'];

    $officeGps = Gps::where('name', 'GPS Point')->first();

    $officeLat = $officeGps->latitude;
    $officeLng = $officeGps->longitude;

    $distance = $this->haversineFormula($userLat, $userLng, $officeLat, $officeLng);

    if ($distance <= 500) {

      if (isset($requestData['check_in_photo'])) {
        $image = $requestData['check_in_photo'];
        $extension = $image->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $imagePath = $image->storeAs('staffImages', $hashedName, 'public');
        $imageUrl = Storage::url($imagePath);
      }

      return CheckIn::create([
        'staff_id' => $requestData['staff_id'],
        'time_shift_id' => $requestData['time_shift_id'],
        'check_in_date_time' => now(),
        'check_in_photo_path' => $imagePath ?? null,
        'check_in_photo_url' => $imageUrl ?? null,
        'is_current_checked_in' => true,
      ]);
    }

    return ['error' => 'Check-in not allowed. You are not within the allowed range.'];
  }

  public function checkOut(array $validatedData, $checkInId)
  {
    $checkIn = CheckIn::find($checkInId);

    if ($checkIn) {
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
      ]);

      return $checkIn;
    }

    return ['error' => 'Check-in not found.'];
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
