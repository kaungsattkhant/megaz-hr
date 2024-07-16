<?php

namespace App\Events;

use App\Models\Entity;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WaiterNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $department_id;
    public $entity;

    public function __construct(Entity $entity,$department_id)
    {
        //
        $this->department_id=$department_id;
        $this->entity = $entity;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("waiter-notification-request.{$this->department_id}"),
        ];
    }
    public function broadcastWith()
    {
        return [
            'department_id' => $this->department_id,
            'entity_id' => $this->entity,
        ];
    }
}
