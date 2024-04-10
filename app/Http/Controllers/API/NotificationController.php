<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    protected $notificationRepo;
    public function __construct(NotificationInterface $notification_interface)
    {
        $this->notificationRepo = $notification_interface;
    }

    public function index(Request $request){
        $notifications = $this->notificationRepo->list($request);
        ResponseData($notifications);
    }

    public function setSeenNotifications(Request $request)
    {
        $notificationIds = json_decode($request->notification_ids, true);
        if(!UserData()->id || !$notificationIds){
            ResponseMessage('Setting notifications as seen failed', 400);
        }

        $isOk = $this->notificationRepo->setSeenNotifications(UserData()->id, $notificationIds);
        if($isOk){
            ResponseMessage('OK');
        }
        else{
            ResponseMessage('Not ok', 400);
        }
    }

    public function markReadNotification(Request $request, $notificationId)
    {
        if(!UserData()->id || !$notificationId){
            ResponseMessage('Mark notification as read failed', 400);
        }

        $isOk = $this->notificationRepo->markReadNotification(UserData()->id, $notificationId);
        if($isOk){
            ResponseMessage('OK');
        }
        else{
            ResponseMessage('Not ok', 400);
        }
    }
}
