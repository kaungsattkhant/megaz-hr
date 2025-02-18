<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [

                'id' => $this->id,
                //    'is_read' => $this->is_read,
                //    'is_read_count' => $this->is_read_count,
                //    'read_at' => $this->read_at,
                'title' => $this->title,
                'preview' => $this->preview,
                // 'staff_id' => $this->staff_id,
                // 'notification_id' => $this->notification_id,
                // 'date_time' => $this->date_time,
                // 'notificationable_id' => $this->notificationable_id,
                // 'notificationable_type' => $this->notificationable_type,
                'notification' => new NotificationResource($this->whenLoaded('notification')),
            ];
    }
}
