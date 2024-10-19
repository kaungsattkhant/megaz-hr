<?php

namespace App\Events;

use App\Models\Entity;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WaiterOrderConfirmNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $department_id;
    public $order;
    public $orderItems;
    public $entity;

    public function __construct(Entity $entity,Order $order, array $orderItems = null, OrderItem $orderItem = null, $department_id)
    {
        $this->department_id = $department_id;
        $this->order = $order;
        $this->entity = $entity;


        if ($orderItems !== null) {
            $this->orderItems = $orderItems;
        } elseif ($orderItem !== null) {
            $this->orderItems = [$orderItem];
        } else {
            $this->orderItems = [];
        }
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("waiter-order-notification.{$this->department_id}"),
        ];
    }

    public function broadcastWith()
    {
        $orderItemsWithMenu = collect($this->orderItems)->map(function ($orderItem) {
            return $orderItem->load('menu.areas');
        });

        ResponseData($orderItemsWithMenu);
        $data= [
            'department_id' => $this->department_id,
            'order' => $this->order,
            'order_items' => $orderItemsWithMenu,
            'entity' => $this->entity
        ];
        return $data;
    }
}
