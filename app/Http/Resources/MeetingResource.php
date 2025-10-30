<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $department = null;
        if ($this->meeting_type === "staff_type" || $this->training_type === "staff_type") {
            $staffParticipants = $this->participants->where('staff_id', '!=', null)->first();
            $department = $staffParticipants->staff->department->name;
        } elseif ($this->meeting_type === "role_type" || $this->training_type === "role_type") {
            $roleParticipant = $this->participants->where('role_id', '!=', null)->first();
            $department = optional($roleParticipant->role->department)->name;
        } elseif ($this->meeting_type === "dep_type" || $this->training_type === "dep_type") {
            $deptParticipants = $this->participants->where('department_id', '!=', null)->first();
            $department = $deptParticipants->department->name;
        }
        return [
            "id" => $this->id,
            "date_time" => $this->date_time,
            "from_date" => $this->from_date,
            "to_date" => $this->to_date,
            "place" => $this->place,
            "chaired_by" => $this->chairedBy->name ?? null,
            "trained_by" => $this->trainedBy->name ?? null,
            "title" => $this->title,
            "description" => $this->description,
            "department" => $department,
        ];
    }
}
