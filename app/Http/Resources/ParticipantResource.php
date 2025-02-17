<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'department_id' => $this->department_id,
            'role_id' => $this->role_id,
            'staff_id' => $this->staff_id,
            'participantable_id' => $this->participantable_id,
            'participantable_type' => $this->participantable_type,
            'department_name' => $this->department->name ?? null,
            'role' => $this->role->name ?? null,
            'staff' => $this->staff->name ?? null,
        ];
    }
}
