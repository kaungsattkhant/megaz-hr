<?php

namespace App\Repositories\StaffTimeShift;

use App\Models\Staff;
use App\Models\StaffTimeshift;
use Illuminate\Support\Facades\DB;
use App\Http\Action\SendNotification\SendNotification;

class StaffTimeShiftRepository implements StaffTimeShiftRepositoryInterface
{

  use SendNotification;
  public function getStaffTimeShifts($request)
  {
    $query = StaffTimeshift::with(['staff.department', 'staff.roles', 'timeshift', 'area'])->orderBy('id', 'desc');
    return (isset($request->per_page) || isset($request->page))
      ? $query->paginate(config('common.list_count'))
      : $query->get();
  }

  public function createStaffTimeShift(array $data)
  {
    // dd($data);
    DB::beginTransaction();
    try {
      if (isset($data['staff_time_shifts'])) {
        $staff_time_shifts = json_decode($data['staff_time_shifts'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for staff_time_shifts.', 400);
        }
        foreach ($staff_time_shifts as $staff_time_shift) {
          $exists = StaffTimeshift::where('date_time', $staff_time_shift['date_time'])
            ->where('staff_id', $staff_time_shift['staff_id'])
            ->where('timeshift_id', $staff_time_shift['timeshift_id'])
            ->exists();

          if ($exists) {
            // Return error or handle as needed
            $staff=Staff::find($staff_time_shift['staff_id']);
            ResponseMessage('Shift already assigned for '.$staff->name.' at this date and timeshift.',422);
          }
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
              'status' => 'pending',
              'created_by' => UserData()->id,
            ]
          );
          $this->sendShiftAssignedNotification($staffTimeshift);
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

  public function updateStaffTimeShiftStatus($id, $data)
  {
    DB::beginTransaction();
    try {
      $staffTimeshift = StaffTimeshift::findOrFail($id);
      $staffTimeshift->update([
        'status' => $data['status'],
      ]);

      if ($data['status'] === "confirmed") {
        $staffTimeshift->update([
          'confirmed_by' => UserData()->id,
          'confirmed_at' => now(),
        ]);
        $this->sendShiftStatusNotificationToAdmin($staffTimeshift, $data['status']);
      }
      if ($data['status'] === "cancelled") {
        $staffTimeshift->update([
          'cancelled_by' => UserData()->id,
          'cancelled_at' => now(),
        ]);
        $this->sendShiftStatusNotificationToAdmin($staffTimeshift, $data['status']);
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
