<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

class LogNotificationSent
{
    public function handle(NotificationSent $event)
    {
       Log::info('Notification sent', [
            'notifiable_id'   => $event->notifiable->id ?? null,
            'notifiable_type' => get_class($event->notifiable),
            'notification'    => get_class($event->notification),
            'channel'         => $event->channel,
            'response'        => $event->response, // allowed ONLY here
        ]);
    }

}
