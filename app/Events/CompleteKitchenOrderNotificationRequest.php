<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Entity;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CompleteKitchenOrderNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roleId;

    public function __construct($roleId)
    {
        $this->roleId = $roleId;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("complete-kitchen-order-notifcaiton-request.{$this->roleId}"),
        ];
    }

    public function broadcastWith()
    {
        $data= [
            'role_id' => $this->roleId,
        ];
        return $data;

    }
}
