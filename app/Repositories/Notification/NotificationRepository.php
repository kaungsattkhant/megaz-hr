<?php

namespace App\Repositories\Notification;

use App\Models\Notification;

class NotificationRepository implements NotificationInterface
{
    public function list($request){
        return Notification::orderBy('id','desc')
        ->with(['notificationUsers'])
        ->whereHas('notificationUsers',function($query){
            $query->where('staff_id',UserData()->id);
        })
        ->get();
    }

}
