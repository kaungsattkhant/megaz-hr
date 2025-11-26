<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\RoomSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
    public $invoice;
    public  $entity_type;

    public function __construct(Customer $customer,Entity $entity, Invoice $invoice,$department_id,Order $order=null, array $orderItem)
    {
        //
        $this->department_id=$department_id;
        $this->invoice_id = $invoice->id;
        $this->customerName = $customer->name;
        $this->roomName = $entity->name;
        $this->entity_type=$entity->entity_type;
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->invoice=$invoice;


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
        Log::info('Room Open Request Reach');
        $invoice=Invoice::find($this->invoice_id);

        $invoiceSession=$invoice->activeInvoiceSession;
        if($this->entity_type=='table'){
            $startDateTime=null;
            $endDateTime=null;
        }else{
            $startDateTime=$invoiceSession->start_date_time;
            $endDateTime=$invoiceSession->end_date_time;
        }
        $data= [
            'department_id' => $this->department_id,
            'invoice_id' => $this->invoice_id,
            'customer_name' => $this->customerName,
            'room_name' => $this->roomName,
            'order' => $this->order,
            'orderItem' => $this->orderItem,
            'start_time' => $startDateTime,
            'end_time' =>$endDateTime ,
            'session_duration' =>$this->entity_type=='table' ? 1 : $invoiceSession->total_session_duration,
        ];
        return $data;

    }
}
