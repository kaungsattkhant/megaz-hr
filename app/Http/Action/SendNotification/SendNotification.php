<?php

namespace App\Http\Action\SendNotification;

use App\Models\Staff;
use App\Models\Notification;
use App\Models\StaffFcmToken;
use App\Models\NotificationUser;
use App\Events\ShiftAssignedEvent;
use Illuminate\Support\Facades\Log;
use App\Events\SendRoleNotification;
use App\Events\SendStaffNotification;
use App\Events\SendStaffJoinNotification;
use App\Events\SendDepartmentNotification;
use App\Events\StaffTimeshiftNotification;
use App\Events\SendNotification as EventsSendNotification;

trait SendNotification
{
    public function send($model, $users, $data)
    {
        $morphMapName = RelationMorphName($model);
        $user_ids = $users->pluck('id');
        // $tokens=$this->getTokensByStaff($user_ids);
        $notification = Notification::create([
            'title' => $data['title'],
            'preview' => $data['body'],
            'date_time' => now(),
            'notificationable_id' => $model->id,
            'notificationable_type' => $morphMapName,
            'created_by' => UserData()->id,
        ]);
        foreach ($user_ids as $user_id) {
            $notification->notificationUsers()->create([
                'staff_id' => $user_id,
            ]);
        }
        // if (count($tokens) > 0) {
        // $data['notification_id']=$notification->id;
        // $data['notificationable_type']=$notification->notificationable_type;
        // $data['notificationable_id']=$notification->notificationable_id;
        // (new Notification())->toUserMultipleDevice($tokens,$data);
        // }]
        if ($users->isNotEmpty()) {
            $role_id = $users->pluck('roles.*.id')->flatten()[0];
            broadcast(new EventsSendNotification($notification, $role_id));
        }
    }

    public function sendParticipantNoti($object, $users, $data)
    {
        $morphMapName = RelationMorphName($object);
        $user_ids = $users->pluck('id');
        $notification = Notification::updateOrCreate(
            [
                'notificationable_id' => $object->id,
                'notificationable_type' => $morphMapName,
            ],
            [
                'title' => $data['title'],
                'preview' => $data['body'],
                'date_time' => now(),
                'created_by' => UserData()->id,
            ]
        );

        foreach ($user_ids as $user_id) {
            $notification->notificationUsers()->updateOrCreate([
                'staff_id' => $user_id,
            ], [
                'title' => $data['title'],
                'preview' => $data['body'],
                'type' => $morphMapName
            ]);
        }
        if ($users->isNotEmpty()) {
            $staff_ids = $users->pluck('id')->filter()->values()->toArray();
            broadcast(new SendDepartmentNotification($notification,$staff_ids,$morphMapName));//this is common broadcast channel for mobilenoti for staff notification
            // if ($type === "dep_type") {
            //     $department_id = $users->first()->department_id;
            //     $department_ids = $users->pluck('department_id')->unique()->filter()->values()->toArray();
                
            //     foreach ($staff_ids as $staff_id) {
            //         broadcast(new SendDepartmentNotification($notification,$staff_ids,$morphMapName));
            //     }
            // } elseif ($type === "role_type") {
            //     // $role_id = $users->pluck('roles.*.id')->flatten()->first();
            //     $staff_ids = $users->pluck('id')->toArray();
            //     // foreach ($staff_ids as $staff_id) {
            //         broadcast(new SendDepartmentNotification($notification,$staff_ids,$morphMapName));
            //     // }
            //     // broadcast(new SendRoleNotification($notification, $role_id));
            // } elseif ($type === "staff_type") {

            //     $staff_ids = $users->pluck('id');
            //     foreach ($staff_ids as $staff_id) {
            //         broadcast(new SendDepartmentNotification($notification, $staff_id,$morphMapName));
            //     }
            // }
        }
    }

    public function LeaveUpdateNotificationRequest($leave, $staff_id)
    {
        try {
            $morphMapName = RelationMorphName($leave);
            $notification = Notification::updateOrCreate(
                [
                    'notificationable_id' => $leave->id,
                    'notificationable_type' => $morphMapName
                ],
                [
                    'title' => 'Leave Status Update',
                    'preview' => "Leave Status Update",
                    'date_time' => now(),
                    'created_by' => UserData()->id,
            ]);

            $notification->notificationUsers()->updateOrCreate([
                'staff_id' => $staff_id,
            ],[
                'title' => 'Leave Status Update',
                'preview' => "Leave Status Update",
            ]);
            broadcast(new SendDepartmentNotification($notification,$staff_id,$morphMapName));
            return $notification;
        } catch (\Exception $e) {
            ResponseMessage($e->getMessage(), 402);
        }
    }

    public function complaintNotification($complaint, $staff_id)
    {
        try {
            $morphMapName = RelationMorphName($complaint);
            $notification = Notification::updateOrCreate(
                [
                    'notificationable_id' => $complaint->id,
                    'notificationable_type' => $morphMapName
                ],
                [
                    'title' => 'Complaints',
                    'preview' => $complaint->title,
                    'date_time' => $complaint->created_at,
                    'created_by' => UserData()->id,
            ]);

            $notification->notificationUsers()->updateOrCreate([
                'staff_id' => $staff_id,
            ],[
                'title' => 'Complaints',
                'preview' => $complaint->title,
            ]);
            broadcast(new SendDepartmentNotification($notification, $staff_id, $morphMapName, $complaint));
            return $notification;
        } catch (\Exception $e) {
            ResponseMessage($e->getMessage(), 402);
        }
    }

    public function getTokensByStaff($user_ids)
    {
        return StaffFcmToken::whereIn('id', $user_ids)->pluck('fcm_token')->toArray();
    }
    public function getUserByRole($department_name, $roles)
    {
        // $staffs=Staff::whereHas('roles',function(query)use($roles){
        //     $query->whereIn('id',$roles);
        // })->get();
        return \App\Models\Staff::with(['roles'])->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })
            ->whereHas('department', function ($query) use ($department_name) {
                $query->where('name', $department_name);
            })
            ->get();
    }
    public function getUserByDepartment($departmentId, $roles)
    {
        // $staffs=Staff::whereHas('roles',function(query)use($roles){
        //     $query->whereIn('id',$roles);
        // })->get();
        return \App\Models\Staff::with(['roles'])->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })
            ->whereHas('department', function ($query) use ($departmentId) {
                $query->where('id', $departmentId);
            })
            ->get();
    }

    public function sendNoti($model, $notiDatas, $data)
    {
        $morphMapName = RelationMorphName($model);
        $notiDatas = collect($notiDatas);

        $notification = Notification::firstOrCreate(
            [
                'notificationable_id' => $model->id,
                'notificationable_type' => $morphMapName,
            ],
            [
                'title' => $data['title'],
                'preview' => $data['body'],
                'date_time' => now(),
                'created_by' => UserData()->id,
            ]
        );

        foreach ($notiDatas as $notidata) {
            $notification->notificationUsers()->create([
                'staff_id' => $notidata['id'],
                'title' => $notidata['title'],
                'preview' => $notidata['preview'],
                'type' => $notidata['type']
            ]);
        }
        // if (count($tokens) > 0) {
        // $data['notification_id']=$notification->id;
        // $data['notificationable_type']=$notification->notificationable_type;
        // $data['notificationable_id']=$notification->notificationable_id;
        // (new Notification())->toUserMultipleDevice($tokens,$data);
        // }]
        $userIds = $notiDatas->pluck('id');
        $users = Staff::whereIn('id', $userIds)->get();

        if ($notiDatas->isNotEmpty()) {
            // $role_id = $users->pluck('roles.*.id')->flatten()[0];
            // broadcast(new EventsSendNotification($notification, $role_id));
            broadcast(new SendDepartmentNotification($notification, $userIds, $morphMapName));
        }
    }

    public function sendStaffJoinNotification($staff)
    {
        try {
            $morphMapName = RelationMorphName($staff);
            $joinDateFormatted = date('d M, Y', strtotime($staff->joined_date));
            $notification = Notification::updateOrCreate(
                [
                    'notificationable_id' => $staff->id,
                    'notificationable_type' => $morphMapName
                ],
                [
                    'title' => 'New Staff Joined',
                    'preview' => "Staff {$staff->name} has officially joined on {$joinDateFormatted}",
                    'date_time' => now(),
                    'created_by' => UserData()->id,
            ]);
            $allStaff = Staff::where('is_cv', 0)->get();
            foreach ($allStaff as $user) {
                $notification->notificationUsers()->updateOrCreate([
                    'staff_id' => $user->id,
                ],[
                    'title' => 'New Staff Joined'
                ]);
            }
            broadcast(new SendStaffJoinNotification($staff, $notification));
            return $notification;
        } catch (\Exception $e) {
            ResponseMessage($e->getMessage(), 402);
        }
    }

    public function sendShiftAssignedNotification($staffTimeshift)
    {
        try {
            $morphMapName = RelationMorphName($staffTimeshift);
            $notification = Notification::updateOrCreate(
                [
                    'notificationable_id' => $staffTimeshift->id,
                    'notificationable_type' => $morphMapName
                ],
                [
                    'title' => 'Shift Assigned',
                    'preview' => "Shift {$staffTimeshift->timeshift->shift->name} has been assigned to {$staffTimeshift->staff->name}",
                    'date_time' => now(),
                    'created_by' => UserData()->id,
            ]);
            $notification->notificationUsers()->updateOrCreate([
                'staff_id' => $staffTimeshift->staff_id,
            ],[
                'title' => 'Shift Assigned',
                'preview' => "Shift {$staffTimeshift->timeshift->shift->name} has been assigned to {$staffTimeshift->staff->name}",
            ]);
            broadcast(new SendDepartmentNotification($notification, $staffTimeshift->staff_id, $morphMapName));

            //broadcast(new StaffTimeshiftNotification($staffTimeshift, $notification));
            return $notification;
        } catch (\Exception $e) {
            ResponseMessage($e->getMessage(), 402);
        }
    }

    public function sendShiftStatusNotificationToAdmin($staffTimeshift , $status)
    {
        try {
            $morphMapName = RelationMorphName($staffTimeshift);
            $notification = Notification::updateOrCreate(
                [
                    'notificationable_id' => $staffTimeshift->id,
                    'notificationable_type' => $morphMapName
                ],
                [
                    'title' => 'Shift Status ' . ucfirst($status),
                    'preview' => "Shift {$staffTimeshift->timeshift->shift->name} has been assigned to {$staffTimeshift->staff->name}",
                    'date_time' => now(),
                    'created_by' => UserData()->id,
            ]);

            $notification->notificationUsers()->updateOrCreate([
                'staff_id' => $staffTimeshift->created_by,
            ],[
                'title' => 'Shift Status ' . ucfirst($status),
                'preview' => "Shift {$staffTimeshift->timeshift->shift->name} has been assigned to {$staffTimeshift->staff->name}",
            ]);
            broadcast(new ShiftAssignedEvent($staffTimeshift, $notification));
            return $notification;
        } catch (\Exception $e) {
            ResponseMessage($e->getMessage(), 402);
        }
    }
}
