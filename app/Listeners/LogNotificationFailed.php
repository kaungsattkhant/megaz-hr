<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;

class LogNotificationFailed
{
    public function handle(NotificationFailed $event)
    {
          Log::error('Notification failed', [
            'notifiable_id'   => $event->notifiable->id ?? null,
            'notifiable_type' => get_class($event->notifiable),
            'notification'    => get_class($event->notification),
            'channel'         => $event->channel,
            'tokens'          => $event->notifiable->routeNotificationForFcm(),
            'data'            => $event->data, // ONLY available for failed
        ]);
    }
}
