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
            'id' => $this->id ?? null,
            'department_id' => $this->department_id ?? null,
            'department_name' => $this->department->name ?? null,
            'role_id' => $this->role_id ?? null,
            'role' => $this->role->name ?? null,
            'staff_id' => $this->staff_id ?? null,
            'staff' => $this->staff->name ?? null,
            // 'participantable_id' => $this->participantable_id,
            // 'participantable_type' => $this->participantable_type,
        ];
    }
}
