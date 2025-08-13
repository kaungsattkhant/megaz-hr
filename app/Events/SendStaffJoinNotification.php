<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendStaffJoinNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $staff;
    public $notification;
    public function __construct($staff, $notification)
    {
        $this->staff = $staff;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("staff-join-notifications.{$this->staff->id}"),
        ];
    }
    public function broadcastWith()
    {
        return [
            'staff_name' => $this->staff->name,
            'staff_id' => $this->staff->id,
            'joined_date' => $this->staff->joined_date,
            'notification_title' => $this->notification->title,
            'notification_body' => $this->notification->preview,
        ];
    }
}
