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
            'objective_name' => $this->objective_name,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name ?? null,
            'created_by' => $this->created_by,
            'is_active' => $this->is_active,
            'total_duration' => $this->objectiveKeys->sum('duration'),
            'objective_keys' => $this->objectiveKeys->map(function ($key) {
                return [
                    'name' => $key->name,
                    'okr_point' => $key->okr_point,
                    'duration' => $key->duration,
                    'assigned_days' => $key->assigned_days,
                ];
            }),
        ];
    }
}
