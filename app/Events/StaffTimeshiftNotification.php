<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\StaffTimeshift;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StaffTimeshiftNotification implements ShouldBroadcast
{ 
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $staffTimeshift;
    public $notification;
    public function __construct(StaffTimeshift $staffTimeshift, Notification $notification)
    {
        $this->staffTimeshift = $staffTimeshift;
        $this->notification = $notification;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("staff-timeshift-assigned.{$this->staffTimeshift->staff_id}"),
        ];
    }

    public function broadcastWith()
    {
        return [
            'staff_name' => $this->staffTimeshift->staff->name,
            'staff_id' => $this->staffTimeshift->staff_id,
            'status' => $this->staffTimeshift->status,
            'shift_name' => $this->staffTimeshift->timeshift->shift->name,
            'date_time' => $this->staffTimeshift->date_time,
            'from_time' => $this->staffTimeshift->timeshift->from_time,
            'to_time' => $this->staffTimeshift->timeshift->to_time,
            'notification_title' => $this->notification->title,
            'notification_body' => $this->notification->preview,
        ];
    }
}
