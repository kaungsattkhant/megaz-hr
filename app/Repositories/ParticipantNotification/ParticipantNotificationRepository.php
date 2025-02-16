<?php

namespace App\Repositories\ParticipantNotification;

use App\Models\Role;
use App\Models\Type;
use App\Models\Staff;
use App\Models\OrgNew;
use App\Models\Meeting;
use App\Models\Warning;
use App\Models\Training;
use App\Models\Department;
use App\Models\Participant;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\NotificationUserResource;
use App\Http\Action\SendNotification\SendNotification;

class ParticipantNotificationRepository implements ParticipantNotificationInterface
{
  use SendNotification;

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

      if (isset($data['meeting_type'])) {
        $this->addParticipantsAndSendNotification($meeting, $data, $data['meeting_type']);
      }

      DB::commit();
      return ResponseData($meeting, 201, true, "Meeting created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false,  $e->getMessage());
    }
  }

  public function getMeetings()
  {
    $meeting = Meeting::with([
      'chairedBy',
      'participants' => function ($query) {
        $query->where('participantable_type', 'meeting');
      },
      // 'participants.staff',
      // 'participants.department',
      // 'participants.role',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
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
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department',
      'participants.role',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
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
      $meeting->participants()->where('participantable_type', 'meeting')->where('participantable_id', $meeting->id)->delete();

      if (isset($data['meeting_type'])) {
        $this->addParticipantsAndSendNotification($meeting, $data, $data['meeting_type']);
      }
      DB::commit();
      return ResponseData($meeting, 200, true, 'Meeting updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while updating the meeting. ' . $e->getMessage());
    }
  }

  public function deleteMeeting($meetingId)
  {
    DB::beginTransaction();
    try {
      $meeting = Meeting::find($meetingId);
      if (!$meeting) {
        return ResponseData(null, 404, false, 'Meeting not found.');
      }
      $meeting->participants()->where('participantable_type', 'meeting')->delete();
      $meeting->delete();
      DB::commit();
      return ResponseData(null, 200, true, 'Meeting deleted successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while deleting the meeting. ' . $e->getMessage());
    }
  }

  public function storeTraining($data)
  {
    DB::beginTransaction();

    try {
      $data['created_by'] = UserData()->id;
      $training = Training::create($data);
      // if (isset($data['type'])) {
      //   $this->createType($data, $training, 'training');
      // }
      if (isset($data['training_type'])) {
        $this->addParticipantsAndSendNotification($training, $data, $data['training_type']);
      }
      DB::commit();
      return ResponseData($training, 201, true, "Training created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false,  $e->getMessage());
    }
  }

  public function getTrainings()
  {
    $training = Training::with([
      // 'type' => function ($query) {
      //   $query->where('typeable_type', 'training');
      // },
      'trainedBy',
      'participants' => function ($query) {
        $query->where('participantable_type', 'training');
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
    return $training;
  }

  public function getTrainingById($trainingId)
  {
    $training = Training::with([
      // 'type' => function ($query) {
      //   $query->where('typeable_type', 'training');
      // },
      'participants' => function ($query) {
        $query->where('participantable_type', 'training');
      },
      'trainedBy',
      'participants.staff',
      // 'participants.staff.department',
      // 'participants.staff.roles',
      'participants.department',
      'participants.role',
      // 'participants.department.roles',
      // 'participants.department.staffs',
      // 'participants.role.department',
      // 'participants.role.staffs',
    ])->find($trainingId);
    if (!$training) {
      return ResponseData(null, 404, false, 'Training not found.');
    }
    return ResponseData($training, 200, true, 'Training details retrieved successfully.');
  }


  public function updateTraining($trainingId)
  {
    DB::beginTransaction();

    try {

      $training = Training::find($trainingId);
      if (!$training) {
        return ResponseData(null, 404, false, 'Training not found.');
      }

      $data = Request::all();
      $data['created_by'] = UserData()->id;
      $training->update($data);

      $training->participants()->where('participantable_type', 'training')->where('participantable_id', $training->id)->delete();
      // if (isset($data['type'])) {
      //   $this->createType($data, $training, 'training');
      // }

      if (isset($data['training_type'])) {
        $this->addParticipantsAndSendNotification($training, $data, $data['training_type']);
      }

      DB::commit();
      return ResponseData($training, 200, true, 'Training updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while updating the training. ' . $e->getMessage());
    }
  }

  public function deleteTraining($trainingId)
  {
    DB::beginTransaction();
    try {
      $training = Training::find($trainingId);
      if (!$training) {
        return ResponseData(null, 404, false, 'Meeting not found.');
      }
      $training->type()->where('typeable_type', 'training')->where('typeable_id', $training->id)->delete();
      $training->participants()->where('participantable_type', 'training')->delete();
      $training->delete();
      DB::commit();
      return ResponseData(null, 200, true, 'Training deleted successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while deleting the training. ' . $e->getMessage());
    }
  }

  public function getOrgNews()
  {
    $orgNew = OrgNew::with([
      'participants' => function ($query) {
        $query->where('participantable_type', 'orgNew');
      },
      'participants.staff',
      'participants.department',
      'participants.role',
      // 'participants.staff.department',
      // 'participants.staff.roles',
      'participants.department.roles',
      // 'participants.department.staffs',
      'participants.role.department',
      // 'participants.role.staffs',
    ])->orderBy('created_at', 'desc')->get();
    return  $orgNew;
  }
  public function getOrgNewsById($orgNewsId)
  {
    $orgNew = OrgNew::with([
      'participants' => function ($query) {
        $query->where('participantable_type', 'orgNew');
      },
      'participants.staff',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department',
      'participants.role',
      'participants.department.roles',
      // 'participants.department.staffs',
      'participants.role.department',
      // 'participants.role.staffs',
    ])->find($orgNewsId);
    if (!$orgNew) {
      return ResponseData(null, 404, false, 'OrgNew not found.');
    }
    return ResponseData($orgNew, 200, true, 'OrgNew details retrieved successfully.');
  }
  public function storeOrgNews($data)
  {
    DB::beginTransaction();

    try {
      $data['created_by'] = UserData()->id;
      $orgNew = OrgNew::create($data);
      if (isset($data['org_news_type'])) {
        $this->addParticipantsAndSendNotification($orgNew, $data, $data['org_news_type']);
      }

      DB::commit();
      return ResponseData($orgNew, 201, true, "OrgNews created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $e->getMessage());
    }
  }
  public function  updateOrgNews($orgNewsId)
  {
    DB::beginTransaction();

    try {

      $orgNew = OrgNew::find($orgNewsId);
      if (!$orgNew) {
        return ResponseData(null, 404, false, 'orgNew not found.');
      }

      $data = Request::all();
      $data['created_by'] = UserData()->id;
      $orgNew->update($data);

      $orgNew->participants()->where('participantable_type', 'orgNew')->where('participantable_id', $orgNew->id)->delete();

      // if (isset($data['type'])) {
      //   $this->createType($data, $orgNew, 'orgNew');
      // }

      if (isset($data['org_news_type'])) {
        $this->addParticipantsAndSendNotification($orgNew, $data, $data['org_news_type']);
      }

      DB::commit();
      return ResponseData($orgNew, 200, true, 'orgNew updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while updating the orgNew. ' . $e->getMessage());
    }
  }

  public function deleteOrgNews($orgNewsId)
  {
    DB::beginTransaction();
    try {
      $orgNew = OrgNew::find($orgNewsId);
      if (!$orgNew) {
        return ResponseData(null, 404, false, 'OrgNews not found.');
      }
      $orgNew->type()->where('typeable_type', 'orgNew')->where('typeable_id', $orgNew->id)->delete();
      $orgNew->participants()->where('participantable_type', 'orgNew')->delete();
      $orgNew->delete();
      DB::commit();
      return ResponseData(null, 200, true, 'orgNew deleted successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while deleting the orgNew. ' . $e->getMessage());
    }
  }

  public function getWarnings($request)
  {
    $query = Warning::with([
      'participants' => function ($query) {
        $query->where('participantable_type', 'warning');
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
    ])->orderBy('created_at', 'desc');

    if ($request->has('from_date') && $request->has('to_date')) {
      $fromDate = $request->input('from_date');
      $toDate = $request->input('to_date');
      $query->whereBetween('date_time', [$fromDate, $toDate]);
    }

    $warning =  $query->get();

    return ResponseData($warning, 200, true, 'Warning retrieved successfully.');
  }

  public function getWarningById($warningId)
  {
    $warning = Warning::with([
      'participants' => function ($query) {
        $query->where('participantable_type', 'warning');
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
    ])->find($warningId);
    if (!$warning) {
      return ResponseData(null, 404, false, 'Warning not found.');
    }
    return ResponseData($warning, 200, true, 'Warning details retrieved successfully.');
  }

  public function storeWarning($data)
  {
    DB::beginTransaction();

    try {
      $data['created_by'] = UserData()->id;
      $warning = Warning::create($data);
      // if (isset($data['type'])) {
      //   $this->createType($data, $warning, 'warning');
      // }
      if (isset($data['warning_type'])) {
        $this->addParticipantsAndSendNotification($warning, $data, $data['warning_type']);
      }

      DB::commit();
      return ResponseData($warning, 201, true, "Warning created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $e->getMessage());
    }
  }

  public function updateWarning($warningId)
  {
    DB::beginTransaction();

    try {

      $warning = Warning::find($warningId);
      if (!$warning) {
        return ResponseData(null, 404, false, 'Warning not found.');
      }

      $data = Request::all();
      $data['created_by'] = UserData()->id;
      $warning->update($data);
      $warning->participants()->where('participantable_type', 'warning')->where('participantable_id', $warning->id)->delete();
      // if (isset($data['type'])) {
      //   $this->createType($data, $warning, 'warning');
      // }
      if (isset($data['warning_type'])) {
        $this->addParticipantsAndSendNotification($warning, $data, $data['warning_type']);
      }

      DB::commit();
      return ResponseData($warning, 200, true, 'Warning updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while updating the Warning. ' . $e->getMessage());
    }
  }

  public function deleteWarning($warningId)
  {
    DB::beginTransaction();
    try {
      $warning = Warning::find($warningId);
      if (!$warning) {
        return ResponseData(null, 404, false, 'Warning not found.');
      }
      $warning->type()->where('typeable_type', 'warning')->where('typeable_id', $warning->id)->delete();
      $warning->participants()->where('participantable_type', 'warning')->delete();
      $warning->delete();
      DB::commit();
      return ResponseData(null, 200, true, 'Warning deleted successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while deleting the warning. ' . $e->getMessage());
    }
  }

  public function storeTypes($data)
  {
    DB::beginTransaction();
    try {
      // $typeData = [
      //   'name' => $data['name'],
      //   'typeable_type' => $data['typeable_type'],
      // ];
      $type = Type::create(
        $data
      );
      DB::commit();
      return ResponseData($type, 201, true, "Type created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while stored the Type. ' . $e->getMessage());
    }
  }

  private function addParticipantsAndSendNotification($object, $data, $type)
  {

    $users = collect();
    $typeName = strtolower(class_basename($object));
    // Check for dep

    if (isset($data['department']) && $type === "dep_type") {

      foreach ($data['department'] as $dep) {
        $participantData = [
          'participantable_id' => $object->id,
          'participantable_type' => $typeName,
          'department_id' => $dep
        ];
        Participant::create($participantData);
        $departmentStaff = Staff::where('department_id', $dep)->get();
        $users = $users->merge($departmentStaff);
      }
    }
    // Check for roles
    if (isset($data['role']) && $type === "role_type") {
      foreach ($data['role'] as $role) {
        $participantData = [
          'participantable_id' => $object->id,
          'participantable_type' => $typeName,
          'role_id' => $role
        ];
        Participant::create($participantData);

        $roleStaff = Staff::whereHas('roles', function ($query) use ($role) {
          $query->where('id', $role);
        })->get();
        $users = $users->merge($roleStaff);
      }
    }
    // Check for specific staff
    if (isset($data['staff']) && $type === "staff_type") {
      foreach ($data['staff'] as $staff) {
        $participantData = [
          'participantable_id' => $object->id,
          'participantable_type' => $typeName,
          'staff_id' => $staff
        ];
        Participant::create($participantData);
        $staffMember = Staff::find($staff);
        if ($staffMember) {
          $users->push($staffMember);
        }
      }
    }
    if ($users->isNotEmpty()) {
      $notificationData = [
        'title' => ucfirst($typeName),
        'body' => 'A new' . $typeName . ' has been scheduled. Please check the details.',
      ];

      $this->sendParticipantNoti($object, $users, $notificationData, $type);
    }
  }

  public function getTypes($request)
  {
    return Type::where('typeable_type', $request->type)->orderBy('created_at', 'desc')->get();
  }

  public function getallNoties($request, $staffId)
  {

    $type = $request->query('type');
    $notifications = NotificationUser::with([
      'notification.notificationable',
      'notification.notificationable.participants',
      'notification.notificationable.participants.department',
      'notification.notificationable.participants.role',
      'notification.notificationable.participants.staff',
    ])
      ->join('notifications', 'notification_users.notification_id', '=', 'notifications.id')
      ->where('notification_users.staff_id', '=', $staffId) // Filter by staff_id
      ->when($type, function ($query) use ($type) {

        if (in_array($type, ['meeting', 'training', 'warning', 'orgNew'])) {
          return $query->where('notifications.notificationable_type', $type);
        }
      })
      ->get();
    foreach ($notifications as $notification) {
      if ($notification->notification->notificationable_type == 'meeting') {
        $notification->notification->notificationable->load('chairedBy');
      }

      if ($notification->notification->notificationable_type == 'training') {
        $notification->notification->notificationable->load('trainedBy');
      }
    }
    // return NotificationUserResource::collection($notifications);
    return ResponseData(NotificationUserResource::collection($notifications), 200, true, "Notifications retrieved successfully.");
  }
}
