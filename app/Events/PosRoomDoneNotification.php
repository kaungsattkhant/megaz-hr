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

class PosRoomDoneNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $role_ids;
    public $entity;



    public function __construct(Entity $entity, $role_ids)
    {
        //
        $this->role_ids = $role_ids;
        $this->entity = $entity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        foreach ($this->role_ids as $department_id) {
            $channels[] = new Channel("pos-roomdone-notification-request.{$department_id}");
        }
        return $channels;
    }
    public function broadcastWith()
    {
        dd($this->role_ids);
        return [
            'role_ids' => $this->role_ids,
            'entity' => $this->entity
        ];
    }
}
