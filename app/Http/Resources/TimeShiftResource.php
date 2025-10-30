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
            'id' => $this->id ?? null,
            'shift_id' => $this->shift_id ?? null,
            'from_time' => $this->from_time ?? null,
            'to_time' => $this->to_time ?? null,
            'shift' => [
                'id' => $this->shift->id ?? null,
                'name' => $this->shift->name ?? null,
            ],
            'area' => [
                'id' => $this->area->id ?? null,
                'name' => $this->area->name ?? null,
            ],
        ];
    }
}
