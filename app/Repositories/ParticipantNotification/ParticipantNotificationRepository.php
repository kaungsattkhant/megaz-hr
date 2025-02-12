<?php

namespace App\Repositories\ParticipantNotification;

use App\Models\Type;
use App\Models\Staff;
use App\Models\Meeting;
use App\Models\OrgNew;
use App\Models\Training;
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
      return ResponseData($meeting, 201, true, "Meeting created successfully.");
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
      if (isset($data['type'])) {
        $typeData = [
          'name' => $data['type'],
          'typeable_id' => $training->id,
          'typeable_type' => 'training',
        ];
        Type::create($typeData);
      }
      if (isset($data['training_type']) && isset($data['department']) && $data['training_type'] === "dep_type") {

        foreach ($data['department'] as $dep) {
          $participantData = [
            'participantable_id' => $training->id,
            'participantable_type' => 'training',
            'department_id' => $dep
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['training_type']) && isset($data['role']) && $data['training_type'] === "role_type") {

        foreach ($data['role'] as $role) {
          $participantData = [
            'participantable_id' => $training->id,
            'participantable_type' => 'training',
            'role_id' => $role
          ];
          Participant::create($participantData);
        }
      }


      if (isset($data['training_type']) && isset($data['staff']) && $data['training_type'] === "staff_type") {

        foreach ($data['staff'] as $staff) {
          $participantData = [
            'participantable_id' => $training->id,
            'participantable_type' => 'training',
            'staff_id' => $staff
          ];
          Participant::create($participantData);
        }
      }
      DB::commit();
      return ResponseData($training, 201, true, "Training created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $extra_message = "An error occurred Training stored.");
    }
  }


  public function getTrainings()
  {
    $training = Training::with([
      'type' => function ($query) {
        $query->where('typeable_type', 'training');
      },
      'trainedBy',
      'createdBy',
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
      'type' => function ($query) {
        $query->where('typeable_type', 'training');
      },
      'participants' => function ($query) {
        $query->where('participantable_type', 'training');
      },
      'trainedBy',
      'createdBy',
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
      $training->type()->where('typeable_type', 'training')->where('typeable_id', $training->id)->delete();
      $training->participants()->where('participantable_type', 'training')->delete();

      if (isset($data['type'])) {
        $typeData = [
          'name' => $data['type'],
          'typeable_id' => $training->id,
          'typeable_type' => 'training',
        ];
        Type::create($typeData);
      }

      if (isset($data['training_type']) && isset($data['department']) && $data['training_type'] === "dep_type") {
        foreach ($data['department'] as $dep) {
          $participantData = [
            'participantable_id' => $training->id,
            'participantable_type' => 'training',
            'department_id' => $dep
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['training_type']) && isset($data['role']) && $data['training_type'] === "role_type") {
        // $training->participants()->where('participantable_type', 'training')->whereNotNull('role_id')->delete();

        foreach ($data['role'] as $role) {
          $participantData = [
            'participantable_id' => $training->id,
            'participantable_type' => 'training',
            'role_id' => $role
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['training_type']) && isset($data['staff']) && $data['training_type'] === "staff_type") {
        // $training->participants()->where('participantable_type', 'training')->whereNotNull('staff_id')->delete();

        foreach ($data['staff'] as $staff) {
          $participantData = [
            'participantable_id' => $training->id,
            'participantable_type' => 'training',
            'staff_id' => $staff
          ];
          Participant::create($participantData);
        }
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
      'type' => function ($query) {
        $query->where('typeable_type', 'orgNew');
      },
      'orgNewsBy',
      'createdBy',
      'participants' => function ($query) {
        $query->where('participantable_type', 'orgNew');
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
    return  $orgNew;
  }
  public function getOrgNewsById($orgNewsId)
  {
    $orgNew = OrgNew::with([
      'type' => function ($query) {
        $query->where('typeable_type', 'orgNew');
      },
      'orgNewsBy',
      'createdBy',
      'participants' => function ($query) {
        $query->where('participantable_type', 'orgNew');
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
      if (isset($data['type'])) {
        $typeData = [
          'name' => $data['type'],
          'typeable_id' =>  $orgNew->id,
          'typeable_type' => 'orgNew',
        ];
        Type::create($typeData);
      }
      if (isset($data['org_news_type']) && isset($data['department']) && $data['org_news_type'] === "dep_type") {

        foreach ($data['department'] as $dep) {
          $participantData = [
            'participantable_id' =>  $orgNew->id,
            'participantable_type' => 'orgNew',
            'department_id' => $dep
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['org_news_type']) && isset($data['role']) && $data['org_news_type'] === "role_type") {

        foreach ($data['role'] as $role) {
          $participantData = [
            'participantable_id' =>  $orgNew->id,
            'participantable_type' => 'orgNew',
            'role_id' => $role
          ];
          Participant::create($participantData);
        }
      }


      if (isset($data['org_news_type']) && isset($data['staff']) && $data['org_news_type'] === "staff_type") {

        foreach ($data['staff'] as $staff) {
          $participantData = [
            'participantable_id' =>  $orgNew->id,
            'participantable_type' => 'orgNew',
            'staff_id' => $staff
          ];
          Participant::create($participantData);
        }
      }
      DB::commit();
      return ResponseData($orgNew, 201, true, "Training created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $extra_message = "An error occurred Training stored.");
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
      $orgNew->type()->where('typeable_type', 'orgNew')->where('typeable_id', $orgNew->id)->delete();
      $orgNew->participants()->where('participantable_type', 'orgNew')->delete();

      if (isset($data['type'])) {
        $typeData = [
          'name' => $data['type'],
          'typeable_id' => $orgNew->id,
          'typeable_type' => 'orgNew',
        ];
        Type::create($typeData);
      }

      if (isset($data['org_news_type']) && isset($data['department']) && $data['org_news_type'] === "dep_type") {
        foreach ($data['department'] as $dep) {
          $participantData = [
            'participantable_id' => $orgNew->id,
            'participantable_type' => 'orgNew',
            'department_id' => $dep
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['org_news_type']) && isset($data['role']) && $data['org_news_type'] === "role_type") {

        foreach ($data['role'] as $role) {
          $participantData = [
            'participantable_id' => $orgNew->id,
            'participantable_type' => 'orgNew',
            'role_id' => $role
          ];
          Participant::create($participantData);
        }
      }

      if (isset($data['org_news_type']) && isset($data['staff']) && $data['org_news_type'] === "staff_type") {

        foreach ($data['staff'] as $staff) {
          $participantData = [
            'participantable_id' => $orgNew->id,
            'participantable_type' => 'orgNew',
            'staff_id' => $staff
          ];
          Participant::create($participantData);
        }
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
}
