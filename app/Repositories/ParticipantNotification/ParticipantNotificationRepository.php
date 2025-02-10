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
      $participants = json_decode($data['participant'], true);
      if (isset($data['meeting_type'])) {

        foreach ($participants as $participant) {
          $participantData = [
            'participantable_id' => $meeting->id,
            'participantable_type' => 'meeting'
          ];

          if ($data['meeting_type'] === "staff_type") {

            $participantData['staff_id'] = $participant['staff_id'];
          } elseif ($data['meeting_type'] === "role_type") {
            $participantData['department_id'] = $participant['department_id'];
            $participantData['role_id'] = $participant['role_id'];
          } elseif ($data['meeting_type'] === "dep_type") {
            $participantData['department_id'] = $participant['department_id'];
          }

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

  public function getMeetings($meetingId = null)
  {
    $meeting = Meeting::with([
      'participants' => function ($query) {
        $query->where('participantable_type', 'meeting');
      },
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
    ])->orderBy('created_at', 'desc');
    if ($meetingId) {
      $meeting->where('id', $meetingId);
    }

    return  $meeting->get();
  }
}
