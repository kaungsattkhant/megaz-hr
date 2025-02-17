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
            'from_date' => $this->from_date ?? null,
            'to_date' => $this->to_date ?? null,
            'place' => $this->place ?? null,
            'description' => $this->description ?? null,
            'type' => $this->notification->notificationable_type ?? null,
            'warning_type' => $this->warning_type ?? null,
            'meeting_type' => $this->meeting_type ?? null,
            'org_news_type' => $this->org_news_type ?? null,
            'training_type' => $this->training ?? null,
            'participants' => ParticipantResource::collection($this->participants),
        ];

        if ($this->notification->notificationable_type === "meeting") {
            $notificationable['chaired_by_id'] = optional($this->chairedBy)->id;
            $notificationable['chaired_by_name'] = optional($this->chairedBy)->name;
        }
        if ($this->notification->notificationable_type === "training") {
            $notificationable['trained_by_id'] = optional($this->trainedBy)->id;
            $notificationable['trained_by_name'] = optional($this->trainedBy)->name;
        }
        return $notificationable;
    }
}
