<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            // 'preview' => $this->preview,
            // 'date_time' => $this->date_time,
            'notificationable_id' => $this->notificationable_id,
            'notificationable_type' => $this->notificationable_type,
            'notificationable' => $this->whenLoaded('notificationable'),
            // 'notificationable' => $this->whenLoaded('notificationable') ? new NotificationableResource($this->notificationable) : null,
        ];
    }
}
