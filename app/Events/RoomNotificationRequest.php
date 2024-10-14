<?php

namespace App\Events;

use App\Models\Customer;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RoomSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $department_id;
    public $invoice_id;
    public $customerName;
    public $roomName;
    public $roomSession;
    public $order;
    public $orderItem;

    public function __construct(Customer $customer,Entity $entity, Invoice $invoice,$department_id,Order $order, array $orderItem)
    {
        //
        $this->department_id=$department_id;
        $this->invoice_id = $invoice->id;
        $this->customerName = $customer->name;
        $this->roomName = $entity->name;
        $this->order = $order;
        $this->orderItem = $orderItem;


    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("room-notification-request.{$this->department_id}"),
        ];
    }
    public function broadcastWith()
    {
        return [
            'department_id' => $this->department_id,
            'invoice_id' => $this->invoice_id,
            'customer_name' => $this->customerName,
            'room_name' => $this->roomName,
            'order' => $this->order,
            'orderItem' => $this->orderItem,
        ];
    }
}
