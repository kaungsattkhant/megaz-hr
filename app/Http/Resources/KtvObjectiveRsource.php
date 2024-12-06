<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KtvObjectiveRsource extends JsonResource
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
            'name' => $this->name,
            'objective_id' => $this->objective->id,
            'objective_name' => $this->objective->objective_name ?? null,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name ?? null,
            'department_id' => $this->role->department_id ?? null,
            'department_name' => $this->role->department->name ?? null,
            'okr_point' => $this->okr_point,
            'duration' => $this->duration,
            'assigned_days' => $this->assigned_days,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
