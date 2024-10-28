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

class KitchenNotificationRequestByArea implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $area_id;
    public $order;
    public $orderItems;
    public $entity;

    public function __construct(Entity $entity,Order $order, $orderItems, $area_id)
    {
        $this->area_id = $area_id;
        $this->order = $order;                          
        $this->entity = $entity;                                                                                                                                                
        $this->orderItems=$orderItems;
        // if ($orderItems !== null) {
        //     $this->orderItems = $orderItems;
        // } elseif ($orderItem !== null) {
        //     $this->orderItems = [$orderItem];
        // } else {
        //     $this->orderItems = [];
        // }
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info('Broadcasting KitchenNotificationRequestByArea', [
            'area_id' => $this->area_id,
            'order_id' => $this->order->id,
            'entity_id' => $this->entity->id
        ]);
        return [
            new Channel("kitchen-notification-request-by-area.{$this->area_id}"),
        ];
    }

    public function broadcastWith()
    {
        
        $data= [
            'area_id' => $this->area_id,
            'order' => $this->order,
            'order_items' => $this->orderItems,
            'entity' => $this->entity
        ];
        return $data;
    }
}
