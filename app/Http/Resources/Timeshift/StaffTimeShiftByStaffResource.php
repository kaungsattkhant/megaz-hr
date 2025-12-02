<?php

namespace App\Http\Resources\Timeshift;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffTimeShiftByStaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "time_shift_id"=>$this->timeshift->id,
            "from_time"=>$this->timeshift?->from_time,
            "to_time"=>$this->timeshift?->to_time,
            "shift_name"=>$this->timeshift->shift->name,
        ];
    }
}
