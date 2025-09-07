<?php

namespace App\Repositories\ParticipantNotification;

use Carbon\Carbon;
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
use App\Models\StaffTimeshift;
use App\Models\NotificationUser;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MeetingResource;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\StaffTimeShiftResource;
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
    ])->orderBy('id', 'desc')->get();
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

  public function updateMeeting($meetingId, $request)
  {
    DB::beginTransaction();

    try {
      $users = collect();
      $data = $request->all();
      $meeting = Meeting::find($meetingId);

      if (!$meeting) {
        return ResponseData(null, 404, false, 'Meeting not found.');
      }
      $data['created_by'] = UserData()->id;
      $meeting->update($data);

      if (isset($data['previous_meeting_type']) && isset($data['meeting_type'])) {
        if ($data['previous_meeting_type'] === $data['meeting_type']) { //for checking the same dep_type update or not 

          if ($data['meeting_type'] === "dep_type" && isset($data['department'])) {

            if (isset($data['deleted_department_ids'])) {
              $depIds = json_decode($data['deleted_department_ids'], true);
              foreach ($depIds as $deleted_department_id) {
                $staffIds = Staff::where('department_id', $deleted_department_id)
                  ->pluck('id');
                $meeting->participants()->where('participantable_type', 'meeting')
                  ->where('participantable_id', $meeting->id)
                  ->where('department_id', $deleted_department_id)->delete();


                NotificationUser::whereHas('notification', function ($query) use ($meeting) {
                  $query->where('notificationable_type', 'meeting')
                    ->where('notificationable_id', $meeting->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['department'] as $dep) {
              $participantData = [
                'participantable_id' =>   $meeting->id,
                'participantable_type' => 'meeting',
                'department_id' => $dep
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $meeting->id, 'participantable_type' => 'meeting', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $users = Staff::whereIn('id', $staffIds)->get();

              $notificationData = [
                'title' => 'Department Update for Meeting',
                'body' => 'A participant’s department has been updated for the meeting. Please check the details.',
              ];
              $this->sendParticipantNoti($meeting, $users, $notificationData, 'dep_type');
            }
          }
          if ($data['meeting_type'] === "role_type" && isset($data['role'])) {

            if (isset($data['deleted_role_ids'])) {
              $delRoleIds = json_decode($data['deleted_role_ids'], true);
              foreach ($delRoleIds as $deleted_role_id) {
                $staffIds = Staff::whereHas('roles', function ($query) use ($deleted_role_id) {
                  $query->where('roles.id', $deleted_role_id);
                })->pluck('id');
                $meeting->participants()->where('participantable_type', 'meeting')->where('participantable_id', $meeting->id)->where('role_id', $deleted_role_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($meeting) {
                  $query->where('notificationable_type', 'meeting')
                    ->where('notificationable_id', $meeting->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['role'] as $role) {
              $participantData = [
                'participantable_id' =>   $meeting->id,
                'participantable_type' => 'meeting',
                'role_id' => $role
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $meeting->id, 'participantable_type' => 'meeting', 'role_id' => $role],
                $participantData
              );

              $roleStaff = Staff::whereHas('roles', function ($query) use ($role) {
                $query->where('id', $role);
              })->get();
              $users = $users->merge($roleStaff);

              $notificationData = [
                'title' => 'Role Update for Meeting',
                'body' => 'A participant’s role has been updated for the meeting. Please check the details.',
              ];
              $this->sendParticipantNoti($meeting, $users, $notificationData, 'role_type');
            }
          }
          if ($data['meeting_type'] === "staff_type" && isset($data['staff'])) {

            if (isset($data['deleted_staff_ids'])) {
              $delStaffIds = json_decode($data['deleted_staff_ids'], true);
              foreach ($delStaffIds as $deleted_staff_id) {
                $staffIds = Staff::where('id', $deleted_staff_id)->pluck('id');
                $meeting->participants()->where('participantable_type', 'meeting')->where('participantable_id', $meeting->id)->where('staff_id', $deleted_staff_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($meeting) {
                  $query->where('notificationable_type', 'meeting')
                    ->where('notificationable_id', $meeting->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['staff'] as $staff) {
              $participantData = [
                'participantable_id' =>   $meeting->id,
                'participantable_type' => 'meeting',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $meeting->id, 'participantable_type' => 'meeting', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $users->push($staffMember);
              }

              $notificationData = [
                'title' => 'Staff Update for Meeting',
                'body' => 'A participant’s department has been updated for the meeting. Please check the details.',
              ];
              $this->sendParticipantNoti($meeting, $users, $notificationData, 'staff_type');
            }
          }
        } else {
          $this->deleteParticipantsAndNotifications($meeting, 'meeting');
          // $meeting->participants()->where('participantable_type', 'meeting')->where('participantable_id', $meeting->id)->delete();

          // $existingNotification =    $meeting->notification()->where('notificationable_id',    $meeting->id)
          //   ->where('notificationable_type',  'meeting')
          //   ->first();
          // $existingNotification->notificationUsers()->delete();
          // $existingNotification->delete();
          if (isset($data['meeting_type'])) {

            $this->addParticipantsAndSendNotification($meeting, $data, $data['meeting_type']);
          }
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
    // DB::beginTransaction();
    // try {
    //   $meeting = Meeting::find($meetingId);
    //   if (!$meeting) {
    //     return ResponseData(null, 404, false, 'Meeting not found.');
    //   }
    //   $meeting->participants()->where('participantable_type', 'meeting')->where('participantable_id', $meeting->id)->delete();
    //   $existingNotification = Notification::where('notificationable_id', $meeting->id)
    //     ->where('notificationable_type',  'meeting')
    //     ->first();
    //   $existingNotification->notificationUsers()->delete();
    //   $meeting->delete();
    //   DB::commit();
    //   return ResponseData(null, 200, true, 'Meeting deleted successfully.');
    // } catch (\Exception $e) {
    //   DB::rollBack();
    //   return ResponseData(null, 422, false, 'An error occurred while deleting the meeting. ' . $e->getMessage());
    // }
  }

  public function storeTraining($data)
  {
    DB::beginTransaction();

    try {
      $data['created_by'] = UserData()->id;
      $training = Training::create($data);

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
      'type',
      'trainedBy',
      'participants' => function ($query) {
        $query->where('participantable_type', 'training');
      },
      // 'participants.staff',
      // 'participants.department',
      // 'participants.role',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
    ])->orderBy('id', 'desc')->get();
    return $training;
  }

  public function getTrainingById($trainingId)
  {
    $training = Training::with([
      'type',
      'participants' => function ($query) {
        $query->where('participantable_type', 'training');
      },
      'trainedBy',
      // 'participants.staff',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department',
      'participants.role',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
    ])->find($trainingId);
    if (!$training) {
      return ResponseData(null, 404, false, 'Training not found.');
    }
    return ResponseData($training, 200, true, 'Training details retrieved successfully.');
  }


  public function updateTraining($trainingId, $request)
  {
    DB::beginTransaction();

    try {
      $users = collect();

      $training = Training::find($trainingId);
      if (!$training) {
        return ResponseData(null, 404, false, 'Training not found.');
      }

      $data = $request->all();

      $data['created_by'] = UserData()->id;
      $training->update($data);
      if (isset($data['previous_training_type']) && isset($data['training_type'])) {
        if ($data['previous_training_type'] === $data['training_type']) { //for checking the same dep_type update or not 

          if ($data['training_type'] === "dep_type" && isset($data['department'])) {

            if (isset($data['deleted_department_ids'])) {
              $depIds = json_decode($data['deleted_department_ids'], true);
              foreach ($depIds as $deleted_department_id) {
                $staffIds = Staff::where('department_id', $deleted_department_id)
                  ->pluck('id');
                $training->participants()->where('participantable_type', 'training')
                  ->where('participantable_id', $training->id)
                  ->where('department_id', $deleted_department_id)->delete();


                NotificationUser::whereHas('notification', function ($query) use ($training) {
                  $query->where('notificationable_type', 'training')
                    ->where('notificationable_id', $training->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['department'] as $dep) {
              $participantData = [
                'participantable_id' =>   $training->id,
                'participantable_type' => 'training',
                'department_id' => $dep
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $training->id, 'participantable_type' => 'training', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $users = Staff::whereIn('id', $staffIds)->get();

              $notificationData = [
                'title' => 'Department Update for Training',
                'body' => 'A participant’s department has been updated for the training. Please check the details.',
              ];
              $this->sendParticipantNoti($training, $users, $notificationData, 'dep_type');
            }
          }
          if ($data['training_type'] === "role_type" && isset($data['role'])) {

            if (isset($data['deleted_role_ids'])) {
              $delRoleIds = json_decode($data['deleted_role_ids'], true);
              foreach ($delRoleIds as $deleted_role_id) {
                $staffIds = Staff::whereHas('roles', function ($query) use ($deleted_role_id) {
                  $query->where('roles.id', $deleted_role_id);
                })->pluck('id');
                $training->participants()->where('participantable_type', 'training')->where('participantable_id', $training->id)->where('role_id', $deleted_role_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($training) {
                  $query->where('notificationable_type', 'training')
                    ->where('notificationable_id', $training->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['role'] as $role) {
              $participantData = [
                'participantable_id' =>   $training->id,
                'participantable_type' => 'training',
                'role_id' => $role
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $training->id, 'participantable_type' => 'training', 'role_id' => $role],
                $participantData
              );

              $roleStaff = Staff::whereHas('roles', function ($query) use ($role) {
                $query->where('id', $role);
              })->get();
              $users = $users->merge($roleStaff);

              $notificationData = [
                'title' => 'Role Update for training',
                'body' => 'A participant’s role has been updated for the training. Please check the details.',
              ];
              $this->sendParticipantNoti($training, $users, $notificationData, 'role_type');
            }
          }
          if ($data['training_type'] === "staff_type" && isset($data['staff'])) {

            if (isset($data['deleted_staff_ids'])) {
              $delStaffIds = json_decode($data['deleted_staff_ids'], true);
              foreach ($delStaffIds as $deleted_staff_id) {
                $staffIds = Staff::where('id', $deleted_staff_id)->pluck('id');
                $training->participants()->where('participantable_type', 'training')->where('participantable_id', $training->id)->where('staff_id', $deleted_staff_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($training) {
                  $query->where('notificationable_type', 'training')
                    ->where('notificationable_id', $training->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['staff'] as $staff) {
              $participantData = [
                'participantable_id' =>   $training->id,
                'participantable_type' => 'training',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $training->id, 'participantable_type' => 'training', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $users->push($staffMember);
              }

              $notificationData = [
                'title' => 'Staff Update for training',
                'body' => 'A participant’s department has been updated for the training. Please check the details.',
              ];
              $this->sendParticipantNoti($training, $users, $notificationData, 'staff_type');
            }
          }
        } else {

          // $training->participants()->where('participantable_type', 'training')->where('participantable_id', $training->id)->delete();

          // $existingNotification =    $training->notification()->where('notificationable_id',    $training->id)
          //   ->where('notificationable_type',  'training')
          //   ->first();
          // $existingNotification->notificationUsers()->delete();
          // $existingNotification->delete();
          $this->deleteParticipantsAndNotifications($training, 'training');
          if (isset($data['training_type'])) {

            $this->addParticipantsAndSendNotification($training, $data, $data['training_type']);
          }
        }
      }

      DB::commit();
      return ResponseData($training, 200, true, 'Training updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData(null, 422, false, 'An error occurred while updating the training. ' . $e->getMessage());
    }
  }


  private function deleteParticipantsAndNotifications($entity, $entityType)
  {
    // $training->participants()->where('participantable_type', 'training')->where('participantable_id', $training->id)->delete();

    // $existingNotification =    $training->notification()->where('notificationable_id',    $training->id)
    //   ->where('notificationable_type',  'training')
    //   ->first();
    // $existingNotification->notificationUsers()->delete();
    // $existingNotification->delete();
    $entity->participants()->where('participantable_type', $entityType)
      ->where('participantable_id', $entity->id)
      ->delete();
    $existingNotification = $entity->notification()->where('notificationable_id', $entity->id)
      ->where('notificationable_type', $entityType)
      ->first();

    if ($existingNotification) {
      $existingNotification->notificationUsers()->delete();
      $existingNotification->delete();
    }
  }

  public function deleteTraining($trainingId)
  {
    // DB::beginTransaction();
    // try {
    //   $training = Training::find($trainingId);
    //   if (!$training) {
    //     return ResponseData(null, 404, false, 'Training not found.');
    //   }
    //   $training->participants()->where('participantable_type', 'training')->delete();
    //   $existingNotification = Notification::where('notificationable_id',  $training->id)
    //     ->where('notificationable_type',  'training')
    //     ->first();
    //   $existingNotification->notificationUsers()->delete();
    //   $existingNotification->delete();
    //   $training->delete();
    //   DB::commit();
    //   return ResponseData(null, 200, true, 'Training deleted successfully.');
    // } catch (\Exception $e) {
    //   DB::rollBack();
    //   return ResponseData(null, 422, false, 'An error occurred while deleting the training. ' . $e->getMessage());
    // }
  }

  public function getOrgNews()
  {
    $orgNew = OrgNew::with([
      'type',
      'participants' => function ($query) {
        $query->where('participantable_type', 'orgNew');
      },
      // 'participants.staff',
      // 'participants.department',
      // 'participants.role',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
    ])->orderBy('id', 'desc')->get();
    return  $orgNew;
  }
  public function getOrgNewsById($orgNewsId)
  {
    $orgNew = OrgNew::with([
      'type',
      'participants' => function ($query) {
        $query->where('participantable_type', 'orgNew');
      },
      'participants.staff',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department',
      'participants.role',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
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
  public function  updateOrgNews($orgNewsId, $data)
  {
    DB::beginTransaction();
    try {
      $users = collect();
      $orgNew = OrgNew::find($orgNewsId);
      if (!$orgNew) {
        return ResponseData(null, 404, false, 'orgNew not found.');
      }
      $data['created_by'] = UserData()->id;
      $orgNew->update($data);

      if (isset($data['previous_org_news_type']) && isset($data['org_news_type'])) {
        if ($data['previous_org_news_type'] === $data['org_news_type']) {

          if ($data['org_news_type'] === "dep_type" && isset($data['department'])) {

            if (isset($data['deleted_department_ids'])) {
              $depIds = json_decode($data['deleted_department_ids'], true);
              foreach ($depIds as $deleted_department_id) {
                $staffIds = Staff::where('department_id', $deleted_department_id)
                  ->pluck('id');
                $orgNew->participants()->where('participantable_type', 'orgNew')
                  ->where('participantable_id', $orgNew->id)
                  ->where('department_id', $deleted_department_id)->delete();


                NotificationUser::whereHas('notification', function ($query) use ($orgNew) {
                  $query->where('notificationable_type', 'orgNew')
                    ->where('notificationable_id', $orgNew->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['department'] as $dep) {
              $participantData = [
                'participantable_id' =>   $orgNew->id,
                'participantable_type' => 'orgNew',
                'department_id' => $dep
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $orgNew->id, 'participantable_type' => 'orgNew', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $users = Staff::whereIn('id', $staffIds)->get();

              $notificationData = [
                'title' => 'Department Update for orgNew',
                'body' => 'A participant’s department has been updated for the orgNew. Please check the details.',
              ];
              $this->sendParticipantNoti($orgNew, $users, $notificationData, 'dep_type');
            }
          }
          if ($data['org_news_type'] === "role_type" && isset($data['role'])) {

            if (isset($data['deleted_role_ids'])) {
              $delRoleIds = json_decode($data['deleted_role_ids'], true);
              foreach ($delRoleIds as $deleted_role_id) {
                $staffIds = Staff::whereHas('roles', function ($query) use ($deleted_role_id) {
                  $query->where('roles.id', $deleted_role_id);
                })->pluck('id');
                $orgNew->participants()->where('participantable_type', 'orgNew')
                  ->where('participantable_id', $orgNew->id)->where('role_id', $deleted_role_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($orgNew) {
                  $query->where('notificationable_type', 'orgNew')
                    ->where('notificationable_id', $orgNew->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['role'] as $role) {
              $participantData = [
                'participantable_id' =>   $orgNew->id,
                'participantable_type' => 'orgNew',
                'role_id' => $role
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $orgNew->id, 'participantable_type' => 'orgNew', 'role_id' => $role],
                $participantData
              );

              $roleStaff = Staff::whereHas('roles', function ($query) use ($role) {
                $query->where('id', $role);
              })->get();
              $users = $users->merge($roleStaff);

              $notificationData = [
                'title' => 'Role Update for orgNew',
                'body' => 'A participant’s role has been updated for the orgNew. Please check the details.',
              ];
              $this->sendParticipantNoti($orgNew, $users, $notificationData, 'role_type');
            }
          }
          if ($data['org_news_type'] === "staff_type" && isset($data['staff'])) {

            if (isset($data['deleted_staff_ids'])) {
              $delStaffIds = json_decode($data['deleted_staff_ids'], true);
              foreach ($delStaffIds as $deleted_staff_id) {
                $staffIds = Staff::where('id', $deleted_staff_id)->pluck('id');
                $orgNew->participants()->where('participantable_type', 'orgNew')->where('participantable_id', $orgNew->id)->where('staff_id', $deleted_staff_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($orgNew) {
                  $query->where('notificationable_type', 'orgNew')
                    ->where('notificationable_id', $orgNew->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['staff'] as $staff) {
              $participantData = [
                'participantable_id' =>   $orgNew->id,
                'participantable_type' => 'orgNew',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $orgNew->id, 'participantable_type' => 'orgNew', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $users->push($staffMember);
              }

              $notificationData = [
                'title' => 'Staff Update for orgNews',
                'body' => 'A participant’s department has been updated for the orgNews. Please check the details.',
              ];
              $this->sendParticipantNoti($orgNew, $users, $notificationData, 'staff_type');
            }
          }
        } else {
          $this->deleteParticipantsAndNotifications($orgNew, 'orgNew');
          if (isset($data['org_news_type'])) {
            $this->addParticipantsAndSendNotification($orgNew, $data, $data['org_news_type']);
          }
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
    // DB::beginTransaction();
    // try {
    //   $orgNew = OrgNew::find($orgNewsId);
    //   if (!$orgNew) {
    //     return ResponseData(null, 404, false, 'OrgNews not found.');
    //   }
    //   $orgNew->participants()->where('participantable_type', 'orgNew')->delete();
    //   $existingNotification = Notification::where('notificationable_id',  $orgNew->id)
    //     ->where('notificationable_type',  'orgNew')
    //     ->first();
    //   $existingNotification->notificationUsers()->delete();
    //   $existingNotification->delete();

    //   $orgNew->delete();
    //   DB::commit();
    //   return ResponseData(null, 200, true, 'orgNew deleted successfully.');
    // } catch (\Exception $e) {
    //   DB::rollBack();
    //   return ResponseData(null, 422, false, 'An error occurred while deleting the orgNew. ' . $e->getMessage());
    // }
  }

  public function getWarnings($request)
  {
    $query = Warning::with([
      'type',
      'participants' => function ($query) {
        $query->where('participantable_type', 'warning');
      },
      // 'participants.staff',
      // 'participants.department',
      // 'participants.role',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
    ])->orderBy('id', 'desc');

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
      'type',
      'participants' => function ($query) {
        $query->where('participantable_type', 'warning');
      },
      // 'participants.staff',
      // 'participants.department',
      // 'participants.role',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department.roles',
      'participants.department.staffs',
      'participants.role.department',
      'participants.role.staffs',
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

  public function updateWarning($warningId, $data)
  {
    DB::beginTransaction();

    try {
      $users = collect();
      $warning = Warning::find($warningId);
      if (!$warning) {
        return ResponseData(null, 404, false, 'Warning not found.');
      }
      $data['created_by'] = UserData()->id;
      $warning->update($data);

      if (isset($data['previous_warning_type']) && isset($data['warning_type'])) {
        if ($data['previous_warning_type'] ===  $data['warning_type']) {

          if ($data['warning_type'] === "dep_type" && isset($data['department'])) {

            if (isset($data['deleted_department_ids'])) {
              $depIds = json_decode($data['deleted_department_ids'], true);
              foreach ($depIds as $deleted_department_id) {
                $staffIds = Staff::where('department_id', $deleted_department_id)
                  ->pluck('id');
                $warning->participants()->where('participantable_type', 'warning')
                  ->where('participantable_id', $warning->id)
                  ->where('department_id', $deleted_department_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($warning) {
                  $query->where('notificationable_type', 'warning')
                    ->where('notificationable_id', $warning->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['department'] as $dep) {
              $participantData = [
                'participantable_id' =>   $warning->id,
                'participantable_type' => 'warning',
                'department_id' => $dep
              ];
              $updatedata = Participant::updateOrCreate(
                ['participantable_id' => $warning->id, 'participantable_type' => 'warning', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $users = Staff::whereIn('id', $staffIds)->get();

              $notificationData = [
                'title' => 'Department Update for warning',
                'body' => 'A participant’s department has been updated for the warning. Please check the details.',
              ];
              $this->sendParticipantNoti($warning, $users, $notificationData, 'dep_type');
            }
          }
          if ($data['warning_type'] === "role_type" && isset($data['role'])) {

            if (isset($data['deleted_role_ids'])) {
              $delRoleIds = json_decode($data['deleted_role_ids'], true);
              foreach ($delRoleIds as $deleted_role_id) {
                $staffIds = Staff::whereHas('roles', function ($query) use ($deleted_role_id) {
                  $query->where('roles.id', $deleted_role_id);
                })->pluck('id');
                $warning->participants()->where('participantable_type', 'warning')->where('participantable_id', $warning->id)->where('role_id', $deleted_role_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($warning) {
                  $query->where('notificationable_type', 'warning')
                    ->where('notificationable_id', $warning->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['role'] as $role) {
              $participantData = [
                'participantable_id' =>   $warning->id,
                'participantable_type' => 'warning',
                'role_id' => $role
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $warning->id, 'participantable_type' => 'warning', 'role_id' => $role],
                $participantData
              );

              $roleStaff = Staff::whereHas('roles', function ($query) use ($role) {
                $query->where('id', $role);
              })->get();
              $users = $users->merge($roleStaff);

              $notificationData = [
                'title' => 'Role Update for warning',
                'body' => 'A participant’s role has been updated for the warning. Please check the details.',
              ];
              $this->sendParticipantNoti($warning, $users, $notificationData, 'role_type');
            }
          }
          if ($data['warning_type'] === "staff_type" && isset($data['staff'])) {

            if (isset($data['deleted_staff_ids'])) {
              $delStaffIds = json_decode($data['deleted_staff_ids'], true);
              foreach ($delStaffIds as $deleted_staff_id) {
                $staffIds = Staff::where('id', $deleted_staff_id)->pluck('id');
                $warning->participants()->where('participantable_type', 'warning')->where('participantable_id', $warning->id)->where('staff_id', $deleted_staff_id)->delete();

                NotificationUser::whereHas('notification', function ($query) use ($warning) {
                  $query->where('notificationable_type', 'warning')
                    ->where('notificationable_id', $warning->id);
                })
                  ->whereIn('staff_id', $staffIds)
                  ->delete();
              }
            }
            foreach ($data['staff'] as $staff) {
              $participantData = [
                'participantable_id' =>   $warning->id,
                'participantable_type' => 'warning',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $warning->id, 'participantable_type' => 'warning', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $users->push($staffMember);
              }

              $notificationData = [
                'title' => 'Staff Update for warning',
                'body' => 'A participant’s department has been updated for the warning. Please check the details.',
              ];
              $this->sendParticipantNoti($warning, $users, $notificationData, 'staff_type');
            }
          }
        } else {
          $this->deleteParticipantsAndNotifications($warning, 'warning');
          if (isset($data['warning_type'])) {
            $this->addParticipantsAndSendNotification($warning, $data, $data['warning_type']);
          }
        }
      }

      // $warning->participants()->where('participantable_type', 'warning')->where('participantable_id', $warning->id)->delete();

      // $existingNotification = $warning->notification()->where('notificationable_id',  $warning->id)
      //   ->where('notificationable_type',  'warning')
      //   ->first();
      // $existingNotification->notificationUsers()->delete();
      // $existingNotification->delete();

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
      $existingNotification = $warning->notification()->where('notificationable_id',  $warning->id)
        ->where('notificationable_type',  'warning')
        ->first();
      $existingNotification->notificationUsers()->delete();
      $existingNotification->delete();
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
        'title' => $typeName,
        'body' => 'A new ' . $typeName . ' has been scheduled. Please check the details.',
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

    // $type = $request->query('type');
    // $notifications = NotificationUser::with([
    //   'notification.notificationable',
    //   'notification.notificationable.participants',
    //   'notification.notificationable.participants.department.roles',
    //   'notification.notificationable.participants.role.department',
    //   'notification.notificationable.participants.staff.department',
    //   'notification.notificationable.participants.staff.roles',
    // ])
    //   ->join('notifications', 'notification_users.notification_id', '=', 'notifications.id')
    //   ->where('notification_users.staff_id', '=', $staffId)
    //   ->when($type, function ($query) use ($type) {
    //     return $query->whereIn('notifications.notificationable_type', ['meeting', 'training', 'warning', 'orgNew'])
    //       ->where('notifications.notificationable_type', $type);
    //   }, function ($query) {
    //     return $query->whereIn('notifications.notificationable_type', ['meeting', 'training', 'warning', 'orgNew']);
    //   })->orderBy('notifications.created_at', 'desc')
    //   ->get();
    // foreach ($notifications as $notification) {
    //   $notificationable = $notification->notification->notificationable;
    //   if ($notificationable) {
    //     switch ($notification->notification->notificationable_type) {
    //       case 'meeting':
    //         $notificationable->load('chairedBy');
    //         break;
    //       case 'training':
    //         $notificationable->load('trainedBy');
    //         break;
    //     }
    //   }
    // }
    // return ResponseData(NotificationUserResource::collection($notifications), 200, true, "Notifications retrieved successfully.");

    $type = $request->query('type');
    $notificationUsers = NotificationUser::with(['notification' => function ($query) use ($type) {
      $query->with('notificationable');
      if ($type) {
        $query->where('notificationable_type', $type)
          ->whereIn('notificationable_type', ['meeting', 'training', 'warning', 'orgNew', 'staff_timeshift']);
      } else {
        $query->whereIn('notificationable_type', ['meeting', 'training', 'warning', 'orgNew', 'staff_timeshift']);
      }
    }])
      ->where('staff_id', $staffId)
      ->orderBy('id', 'desc')
      ->get();
    foreach ($notificationUsers as $notificationUser) {
      $notificationType = $notificationUser->notification->notificationable_type;
      $notificationUser->notification->load(['notificationable']);
      if ($notificationType === 'staff_timeshift') {
        $notificationUser->notification->load([
          'notificationable.timeshift.shift',
          'notificationable.area'
        ]);
      } else if (in_array($notificationType, ['meeting', 'training', 'warning', 'orgNew'])) {
        $notificationUser->notification->load([
          'notificationable.participants',
          'notificationable.participants.department.roles',
          'notificationable.participants.role.department',
          'notificationable.participants.staff.department',
          'notificationable.participants.staff.roles'
        ]);

        if ($notificationType === 'meeting') {
          $notificationUser->notification->load('notificationable.chairedBy');
        } elseif ($notificationType === 'training') {
          $notificationUser->notification->load('notificationable.trainedBy');
        }
      }
    }
    return ResponseData(NotificationUserResource::collection($notificationUsers), 200, true, "Notifications retrieved successfully.");
  }

  public function getMeetingsByStaffId($staffId, $request)
  {
    $staff = Staff::with('department', 'roles')->find($staffId);
    if (!$staff) {
      return ResponseData(null, 404, false, 'Staff not found.');
    }

    $departmentId = $staff->department_id;
    $roleIds = $staff->roles->pluck('id')->toArray();
    $search = $request->query('search');
    $currentDateTime = Carbon::now()->toDateTimeString();
    $meetings = Meeting::with([
      'chairedBy',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department',
      'participants.role',
      'participants.role.department',
    ])
      ->whereHas('participants', function ($query) use ($staffId, $departmentId, $roleIds) {
        $query->where(function ($subQuery) use ($staffId, $departmentId, $roleIds) {
          $subQuery->where('staff_id', $staffId)
            ->orWhere('department_id', $departmentId)
            ->orWhereIn('role_id', $roleIds);
        });
      })
      ->when(isset($search) && $search === "upcoming", function ($query) use ($currentDateTime) {
        return $query->where('date_time', '>=', $currentDateTime);
      })
      ->when(isset($search) && $search === "completed", function ($query) use ($currentDateTime) {
        return $query->where('date_time', '<', $currentDateTime);
      })
      ->orderBy('id', 'desc')
      ->get();
    return ResponseData(MeetingResource::collection($meetings), 200, true, 'Meetings retrieved successfully.');
  }

  public function getTrainingsByStaffId($staffId, $request)
  {
    $staff = Staff::with('department', 'roles')->find($staffId);
    if (!$staff) {
      return ResponseData(null, 404, false, 'Staff not found.');
    }

    $departmentId = $staff->department_id;
    $roleIds = $staff->roles->pluck('id')->toArray();
    $search = $request->query('search');
    $currentDateTime = Carbon::now()->toDateTimeString();
    $trainings = Training::with([
      'trainedBy',
      'participants.staff.department',
      'participants.staff.roles',
      'participants.department',
      'participants.role',
      'participants.role.department',
    ])
      ->whereHas('participants', function ($query) use ($staffId, $departmentId, $roleIds) {
        $query->where(function ($subQuery) use ($staffId, $departmentId, $roleIds) {
          $subQuery->where('staff_id', $staffId)
            ->orWhere('department_id', $departmentId)
            ->orWhereIn('role_id', $roleIds);
        });
      })
      ->when(isset($search) && $search === "upcoming", function ($query) use ($currentDateTime) {
        return $query->where('date_time', '>=', $currentDateTime);
      })
      ->when(isset($search) && $search === "completed", function ($query) use ($currentDateTime) {
        return $query->where('date_time', '<', $currentDateTime);
      })
      ->orderBy('id', 'desc')
      ->get();
    return ResponseData(MeetingResource::collection($trainings), 200, true, 'Trainings retrieved successfully.');
  }

  public function getShiftsByStaffId($staffId, $request)
  {
    $shifts = StaffTimeshift::with('staff', 'timeshift.shift', 'area')->where('staff_id', $staffId)
      ->where('status', 'confirmed')->orderBy('id', 'desc')->paginate(config('common.list_count'));
    if ($shifts->isEmpty()) {
      return [];
    }
    return StaffTimeShiftResource::collection($shifts);
  }

  public function getConfirmedShiftsByStaffIdTimeShiftId($staffId, $staffTimeshiftId)
  {
    $shift = StaffTimeshift::with('staff', 'timeshift.shift', 'area')
      ->where('staff_id', $staffId)
      ->where('id', $staffTimeshiftId)
      ->where('status', 'confirmed')->get();
    if ($shift->isEmpty()) {
      return [];
    }
    return StaffTimeShiftResource::collection($shift);
  }
}
