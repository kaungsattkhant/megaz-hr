<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class mobileCheckInResource extends JsonResource
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
            'staff_id' => $this->staff_id,
            'staff_name' => $this->staff->name,
            'time_shift_id' => $this->time_shift_id,
            'check_in_date_time' => $this->check_in_date_time,
            // 'check_out_date_time' => $this->check_out_date_time,
            // 'check_in_photo_url' => $this->check_in_photo_url,
            // 'check_in_photo_path' => $this->check_in_photo_path,
            // 'check_out_photo_url' => $this->check_out_photo_url,
            // 'check_out_photo_path' => $this->check_out_photo_path,
            // 'is_current_checked_in' => $this->is_current_checked_in,
            // 'total' => $this->calculateTotalHours(),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            // 'time_shift' => new TimeShiftResource($this->whenLoaded('timeShift')),
        ];
    }
}
