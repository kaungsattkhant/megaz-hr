<?php

namespace App\Http\Action\SendNotification;

use App\Models\Notification;
use App\Models\StaffFcmToken;

trait SendNotification
{
    public function send($model,$title,$preview,$users,$data){
        $morphMapName=RelationMorphName($model);
        $user_ids=$users->pluck('id');
        $tokens=$this->getTokensByStaff($user_ids);
        $notification=Notification::create([
            'title'=>$title,
            'preview'=>$preview,
            'date_time'=>now(),
            'notificationable_id'=>$model->id,
            'notificationable_type'=>$morphMapName,
            'created_by'=>UserData()->id,
        ]);
        
        foreach($user_ids as $user_id){
            $notification->notificationUsers()->create([
                'staff_id'=>$user_id,
            ]);
        }
        if (count($tokens) > 0) {
            $data['notification_id']=$notification->id;
            $data['type']=$notification->notificationable_type;
            (new Notification())->toUserMultipleDevice($tokens, $title, $preview, $data);
        }
     
    }

    public function getTokensByStaff($user_ids){
        return StaffFcmToken::whereIn('id',$user_ids)->pluck('fcm_token')->toArray();
    }
    public function getUserByRole($roles){
        // $staffs=Staff::whereHas('roles',function(query)use($roles){
        //     $query->whereIn('id',$roles);
        // })->get();
        return \App\Models\Staff::whereHas('roles', function($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
    }
}
