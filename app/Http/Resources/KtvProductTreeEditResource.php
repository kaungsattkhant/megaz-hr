<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KtvProductTreeEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "entity_id" => $this->entity_id,
            "created_by" => $this->created_by,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'entity' => $this->entity ? [
                'id' => $this->entity->id,
                'name' => $this->entity->name,
                'price_per_hour' => $this->entity->price_per_hour,
                'entity_type' => $this->entity->entity_type,
                'is_available' => $this->entity->is_available,
                'is_active' => $this->entity->is_active,
                'status' => $this->entity->status,
            ] : null,
            'ktv_objectives' => $this->KtvObjectives->map(function ($ktvObjective) {
                $objective = $ktvObjective->objective;
                return [
                    'id' => $ktvObjective->id,
                    'objective_name' => $objective->objective_name,
                    'role_id' => $objective->role_id,
                    'role_name' => $objective->role->name ?? null,
                    'created_by' => $objective->created_by,
                    'is_active' => $objective->is_active,
                    'total_duration' => $objective->objectiveKeys->sum('duration'),
                    "created_at" => $ktvObjective->created_at,
                    "updated_at" => $ktvObjective->updated_at,
                    'objective_keys' => $objective->objectiveKeys->map(function ($key) {
                        return [
                            "id" => $key->id,
                            "objective_id" => $key->objective->id,
                            'name' => $key->name,
                            'okr_point' => $key->okr_point,
                            'duration' => $key->duration,
                            'assigned_days' => $key->assigned_days,
                            "created_at" => $key->created_at,
                            "updated_at" => $key->updated_at,
                        ];
                    }),
                ];
            }),
            'ktv_items' => $this->KtvItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'quantity' => $item->quantity,
                    'item' => $item->item ? [
                        'id' => $item->item->id,
                        'name' => $item->item->name,
                    ] : null,
                ];
            }),
        ];
    }
}
