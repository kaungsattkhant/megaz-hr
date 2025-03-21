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

class OrderNotificationByArea implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $area_id;

    public function __construct($areaId)
    {
        $this->area_id = $areaId;
       
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Log::info('Broadcasting KitchenNotificationRequestByArea', [
        //     'area_id' => $this->area_id,
        //     'order_id' => $this->order->id,
        //     'entity_id' => $this->entity
        // ]);
        return [
            new Channel("order-notification-by-area.{$this->area_id}"),
        ];
    }

    public function broadcastWith()
    {
        Log::info('Reach Order to combine list');
        $data['message']='New Order arrived';
        return $data;

    }
}
