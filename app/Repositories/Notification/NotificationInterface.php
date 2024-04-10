<?php

namespace App\Repositories\Notification;

interface NotificationInterface
{

    public function list($request);

    public function setSeenNotifications($staffId, array $notificationIds);

    public function markReadNotification($staffId, $notificationId);
}
