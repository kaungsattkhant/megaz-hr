<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeShiftResource extends JsonResource
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
            'shift_id' => $this->shift_id,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'shift' => [
                'id' => $this->shift->id,
                'name' => $this->shift->name,
            ],
            'area' => [
                'id' => $this->area->id,
                'name' => $this->area->name,
            ],
        ];
    }
}
