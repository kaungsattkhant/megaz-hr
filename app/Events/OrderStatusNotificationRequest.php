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

class OrderStatusNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $department_id;
    public $order_item;


    public function __construct(OrderItem $order_item,$department_id)
    {
        //
        $this->order_item = $order_item;
        $this->department_id=$department_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("order-status-notification-request.{$this->department_id}"),
        ];
    }
    public function broadcastWith()
    {
        return [
            'order_item' => $this->order_item,
            'department_id' => $this->department_id,
        ];
    }
}
