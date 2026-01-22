<?php

namespace App\Repositories\ParticipantNotification;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Type;
use App\Models\Staff;
use App\Models\OrgNew;
use App\Models\CheckIn;
use App\Models\Meeting;
use App\Models\Warning;
use App\Models\Training;
use App\Models\Department;
use App\Models\Participant;
use App\Models\Notification;
use App\Models\ObjectiveStaff;
use App\Models\StaffTimeshift;
use App\Models\NotificationUser;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MeetingResource;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\mobileCheckInResource;
use App\Http\Resources\StaffTimeShiftResource;
use App\Http\Resources\NotificationUserResource;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Http\Action\SendNotification\SendNotification;
use App\Http\Action\SendNotification\FcmSendNotification;

class ParticipantNotificationRepository implements ParticipantNotificationInterface
{
  use SendNotification, FcmSendNotification;

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
        // $this->sendFcmNotification($staffTimeshift, $staffTimeshift->staff, $notiData);

        $this->addParticipantsAndSendNotification($meeting, $data, $data['meeting_type']);
      }

      DB::commit();
      return ResponseData($meeting, 201, true, "Meeting created successfully.");
    } catch (\Exception $e) {
      DB::rollBack();
      return ResponseData($data = null, $status_code = 422, false, $e->getMessage());
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
      $data = $request->all();
      $meeting = Meeting::find($meetingId);

      if (!$meeting) {
        return ResponseData(null, 404, false, 'Meeting not found.');
      }
      $data['created_by'] = UserData()->id;
      $meeting->update($data);

      if (isset($data['previous_meeting_type']) && isset($data['meeting_type'])) {
        if ($data['previous_meeting_type'] === $data['meeting_type']) { //for checking the same dep_type update or not 
          $allUsers = collect();
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
                'participantable_id' => $meeting->id,
                'participantable_type' => 'meeting',
                'department_id' => $dep
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $meeting->id, 'participantable_type' => 'meeting', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $departmentStaff = Staff::whereIn('id', $staffIds)->get();
              $allUsers = $allUsers->merge($departmentStaff);
              // $staffIds = Staff::where('department_id', $dep)->pluck('id');
              // $users = Staff::whereIn('id', $staffIds)->get();
              // $notificationData = [
              //   'title' => 'Department Update for Meeting',
              //   'body' => 'A participant’s department has been updated for the meeting. Please check the details.',
              // ];
              // $this->sendParticipantNoti($meeting, $users, $notificationData, 'dep_type');
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
                'participantable_id' => $meeting->id,
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
              $allUsers = $allUsers->merge($roleStaff);
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
                'participantable_id' => $meeting->id,
                'participantable_type' => 'meeting',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $meeting->id, 'participantable_type' => 'meeting', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $allUsers->push($staffMember);
              }
            }
          }
          if ($allUsers->isNotEmpty()) {
            $notificationData = [
              'title' => 'Meeting Update',
              'preview' => 'A meeting has been updated. Please check the details.',
            ];
            $this->sendFcmNotification($meeting, $allUsers, $notificationData);
            // $this->sendParticipantNoti($meeting, $allUsers, $notificationData);
          }
        } else {
          $this->deleteParticipantsAndNotifications($meeting, 'meeting');
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
      return ResponseData($data = null, $status_code = 422, false, $e->getMessage());
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

      $training = Training::find($trainingId);
      if (!$training) {
        return ResponseData(null, 404, false, 'Training not found.');
      }

      $data = $request->all();

      $data['created_by'] = UserData()->id;
      $training->update($data);
      if (isset($data['previous_training_type']) && isset($data['training_type'])) {
        if ($data['previous_training_type'] === $data['training_type']) { //for checking the same dep_type update or not 
          $allUsers = collect();
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
                'participantable_id' => $training->id,
                'participantable_type' => 'training',
                'department_id' => $dep
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $training->id, 'participantable_type' => 'training', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $departmentStaff = Staff::whereIn('id', $staffIds)->get();
              $allUsers = $allUsers->merge($departmentStaff);
              // $notificationData = [
              //   'title' => 'Department Update for Training',
              //   'body' => 'A participant’s department has been updated for the training. Please check the details.',
              // ];
              // $this->sendParticipantNoti($training, $users, $notificationData, 'dep_type');
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
                'participantable_id' => $training->id,
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
              $allUsers = $allUsers->merge($roleStaff);

              // $notificationData = [
              //   'title' => 'Role Update for training',
              //   'body' => 'A participant’s role has been updated for the training. Please check the details.',
              // ];
              // $this->sendParticipantNoti($training, $users, $notificationData, 'role_type');
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
                'participantable_id' => $training->id,
                'participantable_type' => 'training',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $training->id, 'participantable_type' => 'training', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $allUsers->push($staffMember);
              }

              // $notificationData = [
              //   'title' => 'Staff Update for training',
              //   'body' => 'A participant’s department has been updated for the training. Please check the details.',
              // ];
              // $this->sendParticipantNoti($training, $users, $notificationData, 'staff_type');
            }
          }
          if ($allUsers->isNotEmpty()) {
            $notificationData = [
              'title' => 'Training Update',
              'preview' => 'A training has been updated. Please check the details.',
            ];
            $this->sendFcmNotification($training, $allUsers, $notificationData);
            // $this->sendParticipantNoti($training, $allUsers, $notificationData);
          }
        } else {
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
    return $orgNew;
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
  public function updateOrgNews($orgNewsId, $data)
  {
    DB::beginTransaction();
    try {
      $allUsers = collect();
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
                'participantable_id' => $orgNew->id,
                'participantable_type' => 'orgNew',
                'department_id' => $dep
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $orgNew->id, 'participantable_type' => 'orgNew', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $departmentStaff = Staff::whereIn('id', $staffIds)->get();
              $allUsers = $allUsers->merge($departmentStaff);

              // $notificationData = [
              //   'title' => 'Department Update for orgNew',
              //   'body' => 'A participant’s department has been updated for the orgNew. Please check the details.',
              // ];
              // $this->sendParticipantNoti($orgNew, $users, $notificationData, 'dep_type');
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
                'participantable_id' => $orgNew->id,
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
              $allUsers = $allUsers->merge($roleStaff);
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
                'participantable_id' => $orgNew->id,
                'participantable_type' => 'orgNew',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $orgNew->id, 'participantable_type' => 'orgNew', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $allUsers->push($staffMember);
              }
            }
          }
          if ($allUsers->isNotEmpty()) {
            $notificationData = [
              'title' => 'orgNew Update',
              'preview' => 'A orgNew has been updated. Please check the details.Please check the details.',
            ];
            $this->sendFcmNotification($orgNew, $allUsers, $notificationData);
            // $this->sendParticipantNoti($orgNew, $allUsers, $notificationData);
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

    $warning = $query->get();

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
      $allUsers = collect();
      $warning = Warning::find($warningId);
      if (!$warning) {
        return ResponseData(null, 404, false, 'Warning not found.');
      }
      $data['created_by'] = UserData()->id;
      $warning->update($data);

      if (isset($data['previous_warning_type']) && isset($data['warning_type'])) {
        if ($data['previous_warning_type'] === $data['warning_type']) {

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
                'participantable_id' => $warning->id,
                'participantable_type' => 'warning',
                'department_id' => $dep
              ];
              $updatedata = Participant::updateOrCreate(
                ['participantable_id' => $warning->id, 'participantable_type' => 'warning', 'department_id' => $dep],
                $participantData
              );

              $staffIds = Staff::where('department_id', $dep)->pluck('id');
              $users = Staff::whereIn('id', $staffIds)->get();
              $allUsers = $allUsers->merge($users);
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
                'participantable_id' => $warning->id,
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
              $allUsers = $allUsers->merge($roleStaff);
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
                'participantable_id' => $warning->id,
                'participantable_type' => 'warning',
                'staff_id' => $staff
              ];
              Participant::updateOrCreate(
                ['participantable_id' => $warning->id, 'participantable_type' => 'warning', 'staff_id' => $staff],
                $participantData
              );
              $staffMember = Staff::find($staff);
              if ($staffMember) {
                $allUsers->push($staffMember);
              }
            }
          }
          if ($allUsers->isNotEmpty()) {
            $notificationData = [
              'title' => 'Warning Update',
              'preview' => 'A warning has been updated. Please check the details.',
            ];
            $this->sendFcmNotification($warning, $users, $notificationData);
            // $this->sendParticipantNoti($warning, $allUsers, $notificationData);
          }
        } else {
          $this->deleteParticipantsAndNotifications($warning, 'warning');
          if (isset($data['warning_type'])) {
            $this->addParticipantsAndSendNotification($warning, $data, $data['warning_type']);
          }
        }
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
      $existingNotification = $warning->notification()->where('notificationable_id', $warning->id)
        ->where('notificationable_type', 'warning')
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
        'preview' => 'A new ' . $typeName . ' has been scheduled. Please check the details.',
      ];
      $this->sendFcmNotification($object, $users, $notificationData);
      // $this->sendParticipantNoti($object, $users, $notificationData);
    }
  }

  public function getTypes($request)
  {
    return Type::where('typeable_type', $request->type)->orderBy('created_at', 'desc')->get();
  }

  public function getallNoties($request, $staffId)
  {
    $type = $request->query('type');
    $validTypes = ['objective_staff', 'meeting', 'training', 'warning', 'orgNew', 'staff_timeshift', 'staff_equipment_handover'];

    $filterTypes = ($type && in_array($type, $validTypes))
      ? [$type]              // filter by requested type
      : $validTypes;       // fallback types
    // $notificationUsers = NotificationUser::with([
    //   'notification' => function ($query) {
    //     $query->with('notificationable');
    //   }
    // ])
    //   ->where('staff_id', $staffId)
    //   ->whereHas('notification', function ($query) use ($filterTypes) {
    //     $query->whereIn('notificationable_type', $filterTypes);
    //   })
    //   ->orderBy('id', 'desc')
    //   ->paginate(config('common.list_count'));

    // $filteredNotifications = $notificationUsers->getCollection()->filter(function ($notificationUser) {
    //   return $notificationUser->notification && $notificationUser->notification->notificationable;
    // });

    // foreach ($filteredNotifications as $notificationUser) {
    //   $notificationType = $notificationUser->notification->notificationable_type;

    //   if (in_array($notificationType, ['meeting', 'training', 'warning', 'orgNew'])) {
    //     $notificationUser->notification->notificationable->load([
    //       'participants',
    //       'participants.department.roles',
    //       'participants.role.department',
    //       'participants.staff.department',
    //       'participants.staff.roles'
    //     ]);

    //     if ($notificationType === Relation::getMorphedModel('meeting') || $notificationType === 'meeting') {
    //       $notificationUser->notification->notificationable->load('chairedBy');
    //     } elseif ($notificationType === Relation::getMorphedModel('training') || $notificationType === 'training') {
    //       $notificationUser->notification->notificationable->load('trainedBy');
    //     }
    //   } elseif ($notificationType === Relation::getMorphedModel('staff_timeshift') || $notificationType === 'staff_timeshift') {
    //     $notificationUser->notification->notificationable->load([
    //       'timeshift.shift',
    //       'area'
    //     ]);
    //   } elseif ($notificationType === Relation::getMorphedModel('staff_equipment_handover') || $notificationType === 'staff_equipment_handover') {
    //     $notificationUser->notification->notificationable->load([
    //       'fromStaff',
    //       'toStaff',
    //       'staffTimeshift',
    //       'staffTimeshift.timeshift',
    //       'staffTimeshift.timeshift.shift',
    //       'staffTimeshift.area'
    //     ]);
    //   }
    // }
    // return ResponseData(NotificationUserResource::collection($filteredNotifications), 200, true, "Notifications retrieved successfully.");

    //optimize version with one query 
    // dd(Relation::getMorphedModel('objective_staff'));
    // dd(NotificationUser::latest()->first()->notification->notificationable);
    $test = NotificationUser::with('notification')
      ->latest()
      ->first();
    // dd(
    //   $test->notification->notificationable_type,
    //   get_class($test->notification->notificationable)
    // );
    $notificationUsers = NotificationUser::with([
      'notification' => function ($query) {
        $query->with([
          'notificationable' => function ($morphQuery) {
            //meeting
            $morphQuery->morphWith([

              Relation::morphMap([
                'objective_staff' => ObjectiveStaff::class,
              ]),
              Relation::getMorphedModel('meeting') ?? 'meeting' => [
                'participants',
                'participants.department.roles',
                'participants.role.department',
                'participants.staff.department',
                'participants.staff.roles',
                'chairedBy',
              ],

              //  Training
              Relation::getMorphedModel('training') ?? 'training' => [
                'participants',
                'participants.department.roles',
                'participants.role.department',
                'participants.staff.department',
                'participants.staff.roles',
                'trainedBy',
              ],

              //  Warning / OrgNew
              Relation::getMorphedModel('warning') ?? 'warning' => [
                'participants',
                'participants.department.roles',
                'participants.role.department',
                'participants.staff.department',
                'participants.staff.roles',
              ],
              Relation::getMorphedModel('orgNew') ?? 'orgNew' => [
                'participants',
                'participants.department.roles',
                'participants.role.department',
                'participants.staff.department',
                'participants.staff.roles',
              ],

              //  Staff Time Shift
              Relation::getMorphedModel('staff_timeshift') ?? 'staff_timeshift' => [
                'timeshift.shift',
                'area',
              ],

              // Equipment Handover
              Relation::getMorphedModel('staff_equipment_handover') ?? 'staff_equipment_handover' => [
                'fromStaff',
                'toStaff',
                'staffTimeshift.timeshift.shift',
                'staffTimeshift.area',
              ],
              //Objective Staff

            ]);
          }
        ]);
      }
    ])
      ->where('staff_id', $staffId)
      ->whereHas('notification', function ($query) use ($filterTypes) {
        $query->whereIn('notificationable_type', $filterTypes);
      })
      ->orderByDesc('id')
      ->paginate(config('common.list_count'));
    // $notificationUsers->getCollection()->transform(function ($item) {
    //   $notification = $item->notification;

    //   if (!$notification) return $item;

    //   if ($notification->notificationable_type === 'objective_staff') {
    //     $model = \App\Models\ObjectiveStaff::find($notification->notificationable_id);

    //     // 👇 THIS makes Laravel think it's eager-loaded
    //     $notification->setRelation('notificationable', $model);
    //   }

    //   return $item;
    // });
    ResponseData(NotificationUserResource::collection($notificationUsers), 200, true, "Notifications retrieved successfully.");
  }

  public function getMeetingsByStaffId($staffId, $request)
  {
    $staff = Staff::with('department', 'roles')->find($staffId);
    if (!$staff) {
      return ResponseData(null, 400, false, 'Staff not found.');
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
        return $query->where('from_date', '>=', $currentDateTime);
      })
      ->when(isset($search) && $search === "completed", function ($query) use ($currentDateTime) {
        return $query->where('to_date', '<', $currentDateTime);
      })
      ->orderBy('id', 'desc')
      ->get();
    return ResponseData(MeetingResource::collection($meetings), 200, true, 'Meetings retrieved successfully.');
  }

  public function getTrainingsByStaffId($staffId, $request)
  {
    $staff = Staff::with('department', 'roles')->find($staffId);
    if (!$staff) {
      return ResponseData(null, 400, false, 'Staff not found.');
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
        return $query->where('from_date', '>=', $currentDateTime);
      })
      ->when(isset($search) && $search === "completed", function ($query) use ($currentDateTime) {
        return $query->where('to_date', '<', $currentDateTime);
      })
      ->orderBy('id', 'desc')
      ->get();
    return ResponseData(MeetingResource::collection($trainings), 200, true, 'Trainings retrieved successfully.');
  }

  public function getShiftsByStaffId($staffId, $request)
  {
    // $shifts = StaffTimeshift::with('staff', 'timeshift.shift', 'area')
    //   ->where('staff_id', $staffId)
    //   ->where('status', 'confirmed')
    //   ->orderBy('id', 'desc')
    //   ->get();
    // $currentDate = now()->format('Y-m-d');
    // $assignedShifts = StaffTimeshift::with('staff', 'timeshift.shift', 'area')
    //   ->join('time_shifts', 'staff_timeshifts.timeshift_id', '=', 'time_shifts.id')
    //   ->where('staff_timeshifts.staff_id', $staffId)
    //   ->where('staff_timeshifts.status', 'confirmed')
    //   ->whereDate('staff_timeshifts.date_time', '>=', $currentDate)
    //   ->orderBy('staff_timeshifts.date_time')  // Then order by assigned date
    //   ->orderBy('time_shifts.from_time')       // First order by shift start time
    //   ->select('staff_timeshifts.*')           // Important: avoid column conflicts
    //   ->get();
    // foreach ($assignedShifts as $assignedShift) {
    //   // dd($assignedShift);
    //   $currentDate = now()->format('Y-m-d');
    //   $checkIn = CheckIn::where('staff_id', $staffId)
    //     ->where('time_shift_id', $assignedShift->timeshift_id)
    //     ->whereDate('check_in_date_time', $currentDate)
    //     ->orderBy('check_in_date_time', 'desc')
    //     ->first();
    //   $assignedShift->check_in = 'check_in';
    //   $assignedShift->check_in_status = 'check_in';

    //   if (!$checkIn) {
    //     $assignedShift->check_in_status= 'check_in';
    //   } elseif (!$checkIn->is_current_checked_in && !is_null($checkIn->is_self_checkout)) {
    //     $assignedShift->check_in_status = 'already_checked_in';
    //     $assignedShift->check_in = new mobileCheckInResource($checkIn);
    //   } else {
    //     $assignedShift->check_in_status = 'check_out';
    //     $assignedShift->check_in = new mobileCheckInResource($checkIn);
    //   }
    // }
    // Carbon::setTestNow(Carbon::parse('2025-12-29 23:10:00'));
    $currentDate = now()->subDay()->format('Y-m-d');
    $today = now()->toDateString();
    $yesterday = now()->subDay()->toDateString();
    // dd($yesterday);
    $assignedShifts = StaffTimeshift::join('time_shifts', 'staff_timeshifts.timeshift_id', '=', 'time_shifts.id')
      ->leftJoin('check_ins', function ($join) use ($currentDate) {
        $join
          // ->on('staff_timeshifts.staff_id', '=', 'check_ins.staff_id')
          ->on('staff_timeshifts.id', '=', 'check_ins.staff_timeshift_id');
        // ->on('staff_timeshifts.timeshift_id', '=', 'check_ins.time_shift_id')
        // ->whereDate('check_ins.check_in_date_time', '=', $currentDate);
      })
      ->with(['staff', 'timeshift.shift', 'area'])
      ->where('staff_timeshifts.staff_id', $staffId)
      ->where('staff_timeshifts.status', 'confirmed')
      // ->whereDate('staff_timeshifts.date_time', '>=', $currentDate)
      // ->where(function ($q) use ($today, $yesterday) {

      //   // Normal shifts today
      //   $q->where(function ($q1) use ($today) {
      //     $q1->whereDate('staff_timeshifts.date_time', $today)
      //       ->whereColumn('time_shifts.from_time', '<', 'time_shifts.to_time');
      //   })
      //   ->orWhere(function ($q2) use ($yesterday) {
      //     $q2->whereDate('staff_timeshifts.date_time', $yesterday)
      //       ->whereColumn('time_shifts.from_time', '>=', 'time_shifts.to_time');
      //   })
      //     // Night shifts (from_time >= to_time)
      //     ->orWhere(function ($q2) use ($today) {
      //       $q2->whereDate('staff_timeshifts.date_time', $today)
      //         ->whereColumn('time_shifts.from_time', '>=', 'time_shifts.to_time')
      //         ->whereTime('time_shifts.from_time', '>=', '21:00:00'); // night-only
      //     });
      // }) //correct 
      ->where(function ($q) use ($today, $yesterday) {

        // 1️⃣ Normal shifts today
        $q->where(function ($q1) use ($today) {
          $q1->whereDate('staff_timeshifts.date_time', $today)
            ->whereColumn('time_shifts.from_time', '<', 'time_shifts.to_time');
        })

          // 2️⃣ Night shifts today OR yesterday
          ->orWhere(function ($q2) use ($today, $yesterday) {
            $q2->whereColumn('time_shifts.from_time', '>=', 'time_shifts.to_time')
              ->whereIn(
                DB::raw('DATE(staff_timeshifts.date_time)'),
                [$today, $yesterday]
              );
          });
      })
      ->orderBy('staff_timeshifts.date_time')
      ->orderBy('time_shifts.from_time')
      ->select(
        'staff_timeshifts.*',
        'check_ins.id as check_in_id',
        'check_ins.is_current_checked_in',
        'check_ins.is_self_checkout',
        'check_ins.check_in_date_time'
      )
      ->get()
      ->map(function ($shift) {
        $checkIn = null;
        if ($shift->check_in_id) {
          $checkIn = new CheckIn();
          $checkIn->id = $shift->check_in_id;
          $checkIn->is_current_checked_in = $shift->is_current_checked_in;
          $checkIn->is_self_checkout = $shift->is_self_checkout;
          $checkIn->check_in_date_time = $shift->check_in_date_time;
        }
        $shift->check_in_status = 'check_in';
        if (!$checkIn) {
          $shift->check_in_status = 'check_in';
          $shift->check_in = 'check_in';
        } elseif (!$checkIn->is_current_checked_in && !is_null($checkIn->is_self_checkout)) {
          $shift->check_in_status = 'already_checked_in';
          $shift->check_in = $checkIn;
        } else {
          $shift->check_in_status = 'check_out';
          $shift->check_in = $checkIn;
        }
        return $shift;
      });
    return StaffTimeShiftResource::collection($assignedShifts);
  }

  private function resolveCheckInStatus($checkIn)
  {
    if (!$checkIn) {
      return 'check_in';
    }

    if (!$checkIn->is_current_checked_in && !is_null($checkIn->is_self_checkout)) {
      return 'already_checked_in';
    }

    return 'check_out';
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
