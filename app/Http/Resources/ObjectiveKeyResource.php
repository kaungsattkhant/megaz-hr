<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectiveKeyResource extends JsonResource
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
            'objective_id' => $this->objective_id,
            'name' => $this->name,
            'okr_point' => $this->okr_point,
            'duration' => $this->duration,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name ?? null,
            'objective' => new ObjectiveResource($this->whenLoaded('objective')),
        ];
    }
}
