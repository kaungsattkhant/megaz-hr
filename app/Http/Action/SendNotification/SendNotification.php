<?php

namespace App\Http\Action\SendNotification;

use App\Models\Notification;

trait SendNotification
{
    public function send($model,$title,$preview,$users){
        $morphMapName=RelationMorphName($model);
        $tokens=$this->getTokensByStaff(UserData()->id);
        $notification=Notification::create([
            'title'=>$title,
            'preview'=>$preview,
            'date_time'=>now(),
            'notificationable_id'=>$model->id,
            'notificationable_type'=>$morphMapName,
            'create_by'=>UserData()->id,
        ]);
        
        $notification->notificationUsers()->create([

        ]);
    }

    public function getTokensByStaff($user_ids){
        
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
