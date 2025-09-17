<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendDepartmentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_ids;
    public $date;
    public $title;
    public $body;
    public $morphMapName;

    public function __construct(Notification $notification,$user_ids,$morphMapName)
    {
        $this->user_ids = is_array($user_ids) ? $user_ids : [$user_ids];
        // $this->department_id = $department_id;
        $this->date = $notification->date_time;
        $this->title = $notification->title;
        $this->body = $notification->preview;
        $this->morphMapName = $morphMapName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // return [
        //     new Channel("send-notification.department.{$this->department_id}"),
        // ];

        Log::info('SendDepartmentNotification broadcasting to channels', [
            'channels' => 'request-notification.staff',
            'user_count' => count($this->user_ids),
            'payload' => [
                'date' => $this->date,
                'title' => $this->title,
                'body' => $this->body,
                'noti_type' => $this->morphMapName,
                'user_ids' => $this->user_ids,
            ]
        ]);

        
        // foreach ($this->user_ids as $user_id) {
        //     $channels[] = new Channel("request-notification.staff");
        // }
        return [
            new Channel("request-notification.staff"),
        ];
    }

    public function broadcastWith()
    {
        return [
            'date' => $this->date,
            'title' => $this->title,
            'body' => $this->body,
            'noti_type' => $this->morphMapName,
            'user_ids' => $this->user_ids,
        ];
    }
}
