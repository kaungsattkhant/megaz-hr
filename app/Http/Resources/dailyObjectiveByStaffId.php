<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ObjectiveKeyResource;

class dailyObjectiveByStaffId extends JsonResource
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
            'objective_id' => $this->objective_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            "in_progressed_at" => $this->in_progressed_at,
            "in_progressed_by" => $this->in_progressed_by,
            "completed_at" =>  $this->completed_at,
            "completed_by" => $this->completed_by,
            "approved_at" => $this->approved_at,
            "approved_by" => $this->approved_by,
            "cancelled_at" => $this->cancelled_at,
            "cancelled_by" => $this->cancelled_by,
            "manager_checked_at" => $this->manager_checked_at,
            "manager_checked_by" => $this->manager_checked_by,
            "objective" => new ObjectiveResource($this->objective),
        ];
    }
}
