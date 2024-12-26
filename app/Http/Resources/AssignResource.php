<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>  $this->id,
            'staff_id' => $this->staff_id,
            'staff_name' => $this->staff->name ?? null,
            'assign_date' => $this->assign_date,
            'department_id' => $this->staff->department->id ?? null,
            'department_name' => $this->staff->department->name ?? null,
            'objective_key_id' => $this->objective_key_id,
            'objective_key' => $this->objectiveKey->name ?? null,
            'role_id' => $this->objectiveKey->role_id ?? null,
            'role_name' => $this->objectiveKey->role->name ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
