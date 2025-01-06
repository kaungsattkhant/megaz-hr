<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TimeShiftResource;

class CheckInResource extends JsonResource
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
            'check_out_date_time' => $this->check_out_date_time,
            'check_in_photo_url' => $this->check_in_photo_url,
            'check_in_photo_path' => $this->check_in_photo_path,
            'check_out_photo_url' => $this->check_out_photo_url,
            'check_out_photo_path' => $this->check_out_photo_path,
            'is_current_checked_in' => $this->is_current_checked_in,
            'total' => $this->calculateTotalHours(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'time_shift' => new TimeShiftResource($this->whenLoaded('timeShift')),
        ];
    }

    private function calculateTotalHours(): ?string
    {
        if ($this->check_out_date_time && $this->check_in_date_time) {
            $checkInTime = Carbon::parse($this->check_in_date_time);
            $checkOutTime = Carbon::parse($this->check_out_date_time);
            $totalMinutes = $checkOutTime->diffInMinutes($checkInTime);
            $hours = abs(intdiv($totalMinutes, 60));
            $minutes = abs($totalMinutes % 60);
            return sprintf('%d hours %d minutes', $hours, $minutes);
        }

        return null;
    }
}
