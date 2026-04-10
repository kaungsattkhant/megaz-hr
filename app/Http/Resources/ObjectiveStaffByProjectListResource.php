<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectiveStaffByProjectListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'objective_name' => $this['objective_name'] ?? null,
            'start_date' => $this['start_date'] ?? null,
            'due_date' => $this['due_date'] ?? null,
            'objective_keys' => $this['objective_keys'] ?? [],
            'objective_staff' => $this['objective_staff'] ?? [],
        ];
    }
}
