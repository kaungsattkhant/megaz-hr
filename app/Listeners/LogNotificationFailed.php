<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;

class LogNotificationFailed
{
    public function handle(NotificationFailed $event)
    {
         Log::error("Notification Failed", [
            'notifiable_id'   => $event->notifiable->id ?? null,
            'tokens'   => $event->notifiable->routeNotificationForFcm() ?? null,
            'notifiable_type' => get_class($event->notifiable),
            'notification'    => get_class($event->notification),
            'channel'         => $event->channel,
            'data'            => $event->data,
            // ❗ FCM does NOT provide $event->response
        ]);
    }
}
