<?php

namespace App\Events;

use App\Models\Customer;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\RoomSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomDoneNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $department_id;
    public $entity;
    public $msg;



    public function __construct(Entity $entity,$msg,$department_id)
    {
        //
        $this->department_id=$department_id;
        $this->entity = $entity;
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
            new Channel("roomdone-notification-request.{$this->department_id}"),
        ];
    }
    public function broadcastWith()
    {
        $data = [
            'department_id' => $this->department_id,
            'entity' => $this->entity,
            'msg' => $this->msg
        ];
        return $data;
    }
}
