<?php

namespace App\Http\Action\SendNotification;

use App\Models\Staff;
use App\Models\Notification;
use App\Models\StaffFcmToken;
use Illuminate\Support\Facades\Log;
use App\Events\SendRoleNotification;
use App\Events\SendStaffNotification;
use App\Events\SendDepartmentNotification;
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

    public function sendParticipantNoti($object, $users, $data, $type)
    {
        $morphMapName = RelationMorphName($object);
        $user_ids = $users->pluck('id');
        // $tokens=$this->getTokensByStaff($user_ids);
        $notification = Notification::create([
            'title' => $data['title'],
            'preview' => $data['body'],
            'date_time' => now(),
            'notificationable_id' => $object->id,
            'notificationable_type' => $morphMapName,
            'created_by' => UserData()->id,
        ]);
        foreach ($user_ids as $user_id) {
            $notification->notificationUsers()->create([
                'staff_id' => $user_id,
            ]);
        }
        if ($users->isNotEmpty()) {
            if ($type === "dep_type") {

                $department_id = $users->first()->department_id;
                broadcast(new SendDepartmentNotification($notification, $department_id));
            } elseif ($type === "role_type") {

                $role_id = $users->pluck('roles.*.id')->flatten()->first();
                broadcast(new SendRoleNotification($notification, $role_id));
            } elseif ($type === "staff_type") {

                $staff_ids = $users->pluck('id');
                foreach ($staff_ids as $staff_id) {
                    broadcast(new SendStaffNotification($notification, $staff_id));
                }
            }
        }
        Log::info('Broadcasting department notification', ['department_id' => $department_id]);
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
            $role_id = $users->pluck('roles.*.id')->flatten()[0];
            broadcast(new EventsSendNotification($notification, $role_id));
        }
    }
}
