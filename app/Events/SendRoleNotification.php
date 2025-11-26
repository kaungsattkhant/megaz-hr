<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendRoleNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $role_id;
    public $date;
    public $title;
    public $body;

    public function __construct(Notification $notification,  $role_id)
    {
        $this->role_id = $role_id;
        $this->date = $notification->date_time;
        $this->title = $notification->title;
        $this->body = $notification->preview;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("send-notification.role.{$this->role_id}"),
        ];
    }

    public function broadcastWith()
    {
        return [
            'date' => $this->date,
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}
