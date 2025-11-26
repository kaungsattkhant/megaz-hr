<?php

namespace App\Events;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\RoomSession;
use App\Models\Staff;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $staff_id;
    public $complaint;
    public $msg;



    public function __construct(Complaint $complaint,$msg,$staff_id)
    {
        //
        $this->staff_id=$staff_id;
        $this->complaint = $complaint;
        $this->msg = $msg;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("complaint-notification-request.{$this->staff_id}"),
        ];
    }
    public function broadcastWith()
    {
        $staff = Staff::find($this->staff_id);
        $data = [
            'staff' => $staff,
            'complaint' => $this->complaint,
            'complaint_by' => $this->complaint->postedBy,
            'msg' => $this->msg
        ];
        return $data;
    }
}
