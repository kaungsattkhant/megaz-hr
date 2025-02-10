<?php

namespace App\Repositories\ParticipantNotification;

use App\Models\Staff;
use App\Models\Meeting;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ParticipantNotificationRepository implements ParticipantNotificationInterface
{
  public function getStaffByDepartmentRole($depId, $roleId)
  {
    return Staff::with('department', 'roles')
      ->where('department_id', $depId)
      ->whereHas('roles', function ($query) use ($roleId) {
        $query->where('id', $roleId);
      })
      ->get();
  }
  public function storeMeetings($data)
  {
    DB::beginTransaction();

    try {
      $data['created_by'] = UserData()->id;
      $meeting = Meeting::create($data);


      if (isset($data['meeting_type']) && isset($data['department']) && $data['meeting_type'] === "dep_type") {

        foreach ($data['department'] as $dep) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting',
            'department_id' => $dep
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['meeting_type']) && isset($data['role']) && $data['meeting_type'] === "role_type") {

        foreach ($data['role'] as $role) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting',
            'role_id' => $role
          ];
          Participant::create($participantData);
        }
      }


      if (isset($data['meeting_type']) && isset($data['staff']) && $data['meeting_type'] === "staff_type") {

        foreach ($data['staff'] as $staff) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting',
            'staff_id' => $staff
          ];
          Participant::create($participantData);
        }
      }
      DB::commit();
      return ResponseData($meeting, 200, true, "Meeting created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $extra_message = "An error occurred Meeting stored.");
    }
  }

  public function getMeetings()
  {
    $meeting = Meeting::with([
      'chairedBy',
      'createdBy',
      'participants' => function ($query) {
        $query->where('participantable_type', 'meeting');
      },
      'participants.staff',
      'participants.department',
      'participants.role',
      // 'participants.staff.department',
      // 'participants.staff.roles',
      // 'participants.department.roles',
      // 'participants.department.staffs',
      // 'participants.role.department',
      // 'participants.role.staffs',
    ])->orderBy('created_at', 'desc')->get();
    return $meeting;
  }

  public function showMeeting($meetingId)
  {
    $meeting = Meeting::with([
      'participants' => function ($query) {
        $query->where('participantable_type', 'meeting');
      },
      'participants.staff',
      // 'participants.staff.department',
      // 'participants.staff.roles',
      'participants.department',
      'participants.role',
      // 'participants.department.roles',
      // 'participants.department.staffs',
      // 'participants.role.department',
      // 'participants.role.staffs',
    ])->find($meetingId);
    if (!$meeting) {
      return ResponseData(null, 404, false, 'Meeting not found.');
    }
    return ResponseData($meeting, 200, true, 'Meeting details retrieved successfully.');
  }

  public function updateMeeting($meetingId)
  {
    DB::beginTransaction();

    try {

      $meeting = Meeting::find($meetingId);
      if (!$meeting) {
        return ResponseData(null, 404, false, 'Meeting not found.');
      }

      $data = Request::all();
      $data['created_by'] = UserData()->id;
      $meeting->update($data);
      $meeting->participants()->where('participantable_type', 'meeting')->delete();
      if (isset($data['meeting_type']) && isset($data['department']) && $data['meeting_type'] === "dep_type") {
        // $meeting->participants()->where('participantable_type', 'meeting')->whereNotNull('department_id')->delete();

        foreach ($data['department'] as $dep) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting',
            'department_id' => $dep
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['meeting_type']) && isset($data['role']) && $data['meeting_type'] === "role_type") {
        // $meeting->participants()->where('participantable_type', 'meeting')->whereNotNull('role_id')->delete();

        foreach ($data['role'] as $role) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting',
            'role_id' => $role
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['meeting_type']) && isset($data['staff']) && $data['meeting_type'] === "staff_type") {
        // $meeting->participants()->where('participantable_type', 'meeting')->whereNotNull('staff_id')->delete();

        foreach ($data['staff'] as $staff) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting',
            'staff_id' => $staff
          ];
          Participant::create($participantData);
        }
      }
      DB::commit();
      return ResponseData($meeting, 200, true, 'Meeting updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while updating the meeting. ' . $e->getMessage());
    }
  }
}
