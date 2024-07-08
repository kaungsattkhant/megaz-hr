<?php
namespace App\Events;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KitchenNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $department_id;
    public $order;
    public $orderItems;

    public function __construct(Order $order, array $orderItems = null, OrderItem $orderItem = null, $department_id)
    {
        $this->department_id = $department_id;
        $this->order = $order;

        if ($orderItems !== null) {
            $this->orderItems = $orderItems;
        } elseif ($orderItem !== null) {
            $this->orderItems = [$orderItem];
        } else {
            $this->orderItems = [];
        }
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("kitchen-notification-request.{$this->department_id}"),
        ];
    }

    public function broadcastWith()
    {
        return [
            'department_id' => $this->department_id,
            'order' => $this->order,
            'order_items' => $this->orderItems
        ];
    }
}
