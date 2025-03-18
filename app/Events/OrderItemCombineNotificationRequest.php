<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderItemCombineNotificationRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $area_id;
    /**
     * Create a new event instance.
     */
    public function __construct($area_id)
    {
        $this->area_id = $area_id;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("combine-notification-request-by-area.{$this->area_id}"),
        ];
    }

    public function broadcastWith()
    {
        return [
            'area_id' => $this->area_id,
            'message' => 'Order items have been combined for this area.',
        ];
    }
}
