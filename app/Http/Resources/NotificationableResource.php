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
            'date_time' => $this->date_time ?? null,
            'from_date' => $this->from_date ?? null,
            'to_date' => $this->to_date ?? null,
            'place' => $this->place ?? null,
            'description' => $this->description ?? null,
            'participants' =>  $this->whenLoaded('participants') ,
            'timeshift_id' => $this->timeshift_id ?? null,
            'staff_id' => $this->staff_id ?? null,
            'status' => $this->status ?? null,
            'timeshift' => $this->whenLoaded('timeshift', function() {
                return [
                    'id' => $this->timeshift->id ?? null,
                    'shift_id' => $this->timeshift->shift_id ?? null,
                    'shift_name' => $this->timeshift->shift->name ?? null,
                    'from_time' => $this->timeshift->from_time ?? null,
                    'to_time' => $this->timeshift->to_time ?? null,
                ];
            }),
            
            'area' => $this->whenLoaded('area', function() {
                return [
                    'id' => $this->area->id ?? null,
                    'name' => $this->area->name ?? null,
                ];
            }),
        ];

        if ($this->notificationable_type === "meeting") {
            $notificationable['type'] = $this->meeting_type ?? null;
            $notificationable['chaired_by_id'] = optional($this->chairedBy)->id;
            $notificationable['name'] = optional($this->chairedBy)->name;
        }
        if ($this->notificationable_type === "training") {
            $notificationable['type'] = $this->training_type ?? null;
            $notificationable['trained_by_id'] = optional($this->trainedBy)->id;
            $notificationable['name'] = optional($this->trainedBy)->name;
        }
        if ($this->notificationable_type === "warning") {
            $notificationable['type'] = $this->warning_type ?? null;
        }
        if ($this->notificationable_type === "orgNew") {
            $notificationable['type'] = $this->org_news_type ?? null;
        }

        return $notificationable;
    }
}
