<?php

namespace App\Events;

use App\Models\Customer;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\RoomSession;
use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDoneNotificationRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $role_id;
    public $task;
    public $msg;



    public function __construct(Task $task,$msg,$role_id)
    {
        //
        $this->role_id=$role_id;
        $this->task = $task;
        $this->msg = $msg;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("task-done-notification.{$this->role_id}"),
        ];
    }
    public function broadcastWith()
    {
        $data = [
            'role_id' => $this->role_id,
            'task' => $this->task,
            'msg' => $this->msg
        ];
        return $data;
    }
}
