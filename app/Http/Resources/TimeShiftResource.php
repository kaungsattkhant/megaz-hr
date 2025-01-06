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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'shift' => [
                'id' => $this->shift->id,
                'name' => $this->shift->name,
                'created_at' => $this->shift->created_at,
                'updated_at' => $this->shift->updated_at,
            ],
        ];
    }
}
