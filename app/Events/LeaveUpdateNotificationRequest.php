<?php

namespace App\Events;

use App\Models\Leave;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeaveUpdateNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leave;
    public $staffId;
    /**
     * Create a new event instance.
     */
    public function __construct(Leave $leave, $staffId)
    {
        $this->leave = $leave;
        $this->staffId = $staffId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('staff.' . $this->staffId),
        ];
    }

    public function broadcastWith()
    {
        return [
            'leave_id' => $this->leave->id,
            'leave_title' => $this->leave->title,
            'confirmed_at' => $this->leave->confirmed_at,
            'confirmed_by' => $this->leave->confirmed_by,
            'cancelled_at' => $this->leave->cancelled_at,
            'cancelled_by' => $this->leave->cancelled_by,
            'status' => $this->leave->status,
        ];
    }
}
