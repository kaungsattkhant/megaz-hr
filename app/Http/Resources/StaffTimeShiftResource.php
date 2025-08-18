<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffTimeShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "date_time"=> $this->date_time,
            "staff_id"=> $this->staff_id,
            "staff_name"=> $this->staff->name,
            "timeshift_id"=> $this->timeshift_id,
            "area_id"=> $this->area_id,
            "status"=> $this->status,
            "timeshift" => [
                "id"=> $this->timeshift->id,
                "shift_id"=> $this->timeshift->shift_id,
                "shift_name"=> $this->timeshift->shift->name,
                "from_time"=> $this->timeshift->from_time,
                "to_time"=> $this->timeshift->to_time,
            ],
            "area" => [
                "id"=> $this->area->id,
                "name"=> $this->area->name,
            ],
        ];
    }
}
