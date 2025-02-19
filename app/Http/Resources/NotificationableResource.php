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
            // 'type' => $this->notification->notificationable_type ?? null,
            // 'participants' => ParticipantResource::collection($this->participants),
            'participants' =>  $this->whenLoaded('participants')
        ];

        if ($this->notification->notificationable_type === "meeting") {
            $notificationable['meeting_type'] = $this->meeting_type ?? null;
            $notificationable['chaired_by_id'] = optional($this->chairedBy)->id;
            $notificationable['chaired_by_name'] = optional($this->chairedBy)->name;
        }
        if ($this->notification->notificationable_type === "training") {
            $notificationable['training_type'] = $this->training_type ?? null;
            $notificationable['trained_by_id'] = optional($this->trainedBy)->id;
            $notificationable['trained_by_name'] = optional($this->trainedBy)->name;
        }
        if ($this->notification->notificationable_type === "warning") {
            $notificationable['warning_type'] = $this->warning_type ?? null;
        }
        if ($this->notification->notificationable_type === "orgNew") {
            $notificationable['org_news_type'] = $this->org_news_type ?? null;
        }
        return $notificationable;
    }
}
