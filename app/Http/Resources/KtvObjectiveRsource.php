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
            'objective_name' => $this->objective_name,
            'created_by' => $this->created_by,
            'is_active' => $this->is_active,
            'total_duration' => $this->objectiveKeys->sum('duration'),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'objective_keys' => $this->objectiveKeys->map(function ($key) {
                return [
                    'id' => $key->id,
                    'objective_id' => $key->objective->id,
                    'role_id' => $key->role_id,
                    'role_name' => $key->role->name ?? null,
                    'name' => $key->name,
                    'okr_point' => $key->okr_point,
                    'duration' => $key->duration,
                    'assigned_days' => $key->assigned_days,
                    "created_at" => $key->created_at,
                    "updated_at" => $key->updated_at,
                ];
            }),
        ];
    }
}
