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
}
