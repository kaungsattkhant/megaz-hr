<?php

namespace App\Repositories\StaffTimeShift;

use App\Models\StaffTimeshift;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;

class StaffTimeShiftRepository implements StaffTimeShiftRepositoryInterface
{

  public function getStaffTimeShifts($request)
  {
    $query = StaffTimeshift::with(['staff.department', 'staff.roles', 'timeshift', 'area'])->orderBy('id', 'desc');
    return (isset($request->per_page) || isset($request->page))
      ? $query->paginate(config('common.list_count'))
      : $query->get();
  }

  public function createStaffTimeShift(array $data)
  {
    DB::beginTransaction();
    try {
      if (isset($data['staff_time_shifts'])) {
        $staff_time_shifts = json_decode($data['staff_time_shifts'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for staff_time_shifts.', 400);
        }
        foreach ($staff_time_shifts as   $staff_time_shift) {
          $staffTimeshift = StaffTimeshift::updateOrCreate(
            [
              'date_time' => $staff_time_shift['date_time'],
              'staff_id' => $staff_time_shift['staff_id'],
              'timeshift_id' => $staff_time_shift['timeshift_id'],
            ],
            [
              'date_time' => $staff_time_shift['date_time'],
              'staff_id' => $staff_time_shift['staff_id'],
              'timeshift_id' => $staff_time_shift['timeshift_id'],
              'area_id' => $staff_time_shift['area_id'] ?? null,
            ]
          );
        }
      }
      DB::commit();
      ResponseData($staffTimeshift, 201);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
