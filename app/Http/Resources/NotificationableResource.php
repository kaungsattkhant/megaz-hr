<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ParticipantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $notificationable = [
            'id' => $this->id,
            'title' => $this->title,
            'date_time' => $this->date_time,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'place' => $this->place,
            'description' => $this->description,
            'type' => $this->notificationable_type,
            'participants' => ParticipantResource::collection($this->participants),
        ];

        if ($this->notificationable_type === "meeting") {
            $notificationable['chaired_by_name'] = optional($this->chaired_by)->name;
            $notificationable['chaired_by_id'] = optional($this->chaired_by)->id;
        }

        // Check if the notificationable type is 'training' and add the 'trained_by' name
        if ($this->notificationable_type === "training") {
            $notificationable['trained_by_name'] = optional($this->trained_by)->name;
            $notificationable['trained_by_id'] = optional($this->trained_by)->id;
        }


        return $notificationable;
    }
}
