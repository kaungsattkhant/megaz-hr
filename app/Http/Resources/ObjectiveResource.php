<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectiveResource extends JsonResource
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
            'objective_name' => $this->objective_name,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name ?? null,
            'is_active' => $this->is_active,
            'okr_point' => $this->okr_point,
            'type' => $this->type,
            'repetition' => $this->repetition,
            'sop_id' => $this->sop_id,
            'sop_name' => $this->sop->sop ?? null,
            'objective_keys' => $this->objectiveKeys ? $this->objectiveKeys->map(function($key) {
        return [
            'id' => $key->id,
            'objective_id' => $key->objective_id,
            'name' => $key->name,
        ];
    }) : [],
        ];
    }
}
