<?php

namespace App\Repositories\StaffTimeShift;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\StaffTimeshift;
use Illuminate\Support\Facades\DB;
use App\Http\Action\SendNotification\SendNotification;
use App\Http\Action\SendNotification\FcmSendNotification;

class StaffTimeShiftRepository implements StaffTimeShiftRepositoryInterface
{

  use SendNotification, FcmSendNotification;
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
      // if (isset($data['staff_time_shifts'])) {
      //   $staff_time_shifts = json_decode($data['staff_time_shifts'], true);
      //   if (json_last_error() !== JSON_ERROR_NONE) {
      //     return ResponseMessage('Invalid JSON data provided for staff_time_shifts.', 400);
      //   }
      //   foreach ($staff_time_shifts as $staff_time_shift) {
      //     $exists = StaffTimeshift::where('date_time', $staff_time_shift['date_time'])
      //       ->where('staff_id', $staff_time_shift['staff_id'])
      //       ->where('timeshift_id', $staff_time_shift['timeshift_id'])
      //       ->exists();

      //     if ($exists) {
      //       // Return error or handle as needed
      //       $staff = Staff::find($staff_time_shift['staff_id']);
      //       ResponseMessage('Shift already assigned for ' . $staff->name . ' at this date and timeshift.', 422);
      //     }
      //     $staffTimeshift = StaffTimeshift::create(
      //       [
      //         'date_time' => $staff_time_shift['date_time'],
      //         'staff_id' => $staff_time_shift['staff_id'],
      //         'timeshift_id' => $staff_time_shift['timeshift_id'],
      //         'area_id' => $staff_time_shift['area_id'] ?? null,
      //         'status' => 'pending',
      //         'created_by' => UserData()->id,
      //       ]
      //     );
      //     $notiData['title'] = 'Shift Assigned';
      //     $notiData['date_time']=$staff_time_shift['date_time'];
      //     $notiData['preview'] = "Shift {$staffTimeshift->timeshift->shift->name} has been assigned to {$staffTimeshift->staff->name} for {$staff_time_shift['date_time']}";
      //     $this->sendFcmNotification($staffTimeshift, $staffTimeshift->staff, $notiData);
      //     // $this->sendShiftAssignedNotification($staffTimeshift);
      //   }
      //   //bulk insert 

      // }
      if (!isset($data['staff_time_shifts'])) {
         ResponseMessage('staff_time_shifts is required.', 400);
      }

      $staff_time_shifts = json_decode($data['staff_time_shifts'], true);

      if (json_last_error() !== JSON_ERROR_NONE) {
         ResponseMessage('Invalid JSON data provided for staff_time_shifts.', 400);
      }

      // Convert all date_time to Carbon for consistency
      foreach ($staff_time_shifts as &$sts) {
        $sts['date_time'] = Carbon::parse($sts['date_time'])->format('Y-m-d H:i:s');
      }
      unset($sts);

      // Extract combinations for duplicate detection
      $checkTuples = collect($staff_time_shifts)->map(function ($row) {
        return [
          'date_time'     => $row['date_time'],
          'staff_id'      => $row['staff_id'],
          'timeshift_id'  => $row['timeshift_id'],
        ];
      });

      // Query in ONE shot
      $existing = StaffTimeshift::where(function ($q) use ($checkTuples) {
        foreach ($checkTuples as $t) {
          $q->orWhere(function ($x) use ($t) {
            $x->where('date_time', $t['date_time'])
              ->where('staff_id', $t['staff_id'])
              ->where('timeshift_id', $t['timeshift_id']);
          });
        }
      })->get();

      // If duplicates exist → return error
      if ($existing->count() > 0) {
        $duplicate = $existing->first();
        $staff = Staff::find($duplicate->staff_id);

        return ResponseMessage(
          "Shift already assigned for {$staff->name} at {$duplicate->date_time}.",
          422
        );
      }

      // Add created_by + status to all rows
      $insertData = collect($staff_time_shifts)->map(function ($row) {
        return [
          'date_time'     => $row['date_time'],
          'staff_id'      => $row['staff_id'],
          'timeshift_id'  => $row['timeshift_id'],
          'area_id'       => $row['area_id'] ?? null,
          'status'        => 'pending',
          'created_by'    => UserData()->id,
          'created_at'    => now(),
          'updated_at'    => now(),
        ];
      });

      // Bulk Insert
      StaffTimeshift::insert($insertData->toArray());

      // Send notifications for each created shift
      foreach ($insertData as $row) {
        $shift = StaffTimeshift::where('date_time', $row['date_time'])
          ->where('staff_id', $row['staff_id'])
          ->where('timeshift_id', $row['timeshift_id'])
          ->first();

        $notiData = [
          'title'     => 'Shift Assigned',
          'date_time' => $row['date_time'],
          'preview'   => "Shift {$shift->timeshift->shift->name} has been assigned to {$shift->staff->name} for {$row['date_time']}",
        ];
        $this->sendFcmNotification($shift, $shift->staff, $notiData);
      }
      DB::commit();
      ResponseMessage('Shifts assigned successfully', 200);
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
