<?php

namespace App\Http\Resources\Timeshift;

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
            "id"=>$this->id,
            "from_time"=>$this->timeshfit->from_time,
            "to_time"=>$this->timeshfit->to_time,
            "shift_name"=>$this->timeshift->shift->name,
        ];
    }
}
