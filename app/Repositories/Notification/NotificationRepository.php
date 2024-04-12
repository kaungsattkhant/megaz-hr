<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Models\NotificationUser;
use Exception;
use Illuminate\Support\Facades\DB;

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

    public function setSeenNotifications($staffId, array $notificationIds)
    {
        $userNotifications = NotificationUser::where('staff_id', $staffId)
        ->whereIn('notification_id', $notificationIds)
        ->where('is_read_count', 0)
        ->get();

        if($userNotifications && count($userNotifications) > 0){
            DB::beginTransaction();
            try{
                foreach($userNotifications as $userNotification){
                    $userNotification->is_read_count = 1;
                    $userNotification->save();
                }
                DB::commit();

                return true;
            }
            catch(Exception $e){
                DB::rollBack();

                return false;
            }

        }

        return true;
    }

    public function markReadNotification($staffId, $notificationId)
    {
        $userNotification = NotificationUser::where('staff_id', $staffId)
        ->where('notification_id', $notificationId)
        ->where('is_read', 0)
        ->first();

        if($userNotification){
            DB::beginTransaction();
            try{
                $userNotification->is_read = 1;
                $userNotification->save();
                DB::commit();

                return true;
            }
            catch(Exception $e){
                DB::rollBack();

                return false;
            }
        }

        return true;
    }

}
