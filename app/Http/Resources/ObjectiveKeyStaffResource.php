<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectiveKeyStaffResource extends JsonResource
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
            'staff_name' => $this->staff->name ?? null,
            'objective_key_duty_id' => $this->objective_key_duty_id ?? null,
            'department_id' => $this->staff->department->id ?? null,
            'department_name' => $this->staff->department->name ?? null,
            'objective_key_id' => $this->objective_key_id ?? null,
            'objective_key_name' => $this->objectiveKey->name ?? null,
            'okr_point' => $this->okr_point,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'objective_key' => new ObjectiveKeyResource($this->whenLoaded('objectiveKey')),
            'staff' => new StaffResource($this->whenLoaded('staff')),
        ];
    }
}
