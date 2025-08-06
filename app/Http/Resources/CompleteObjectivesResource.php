<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompleteObjectivesResource extends JsonResource
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
            'okr_point' => $this->okr_point,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name,
            'type' => $this->type,
            'repetition' => $this->repetition ?? null,
            'objective_keys' => $this->whenLoaded('objectiveKeys') 
                ? $this->objectiveKeys->map(function($key) {
                    return [
                        'id' => $key->id,
                        'objective_id' => $key->objective_id,
                        'name' => $key->name,
                    ];
                }) 
                : [],
                'objective_staff' => $this->whenLoaded('objectiveAssigns') 
                ? collect($this->objectiveAssigns)->flatMap(function($assign) {
                    return $assign->objectiveStaff->map(function($objectiveStaff) {
                        return [
                            'id' => $objectiveStaff->id,
                            'start_date' => $objectiveStaff->start_date,
                            'end_date' => $objectiveStaff->end_date,
                            'status' => $objectiveStaff->status,
                            'completed_at' => $objectiveStaff->completed_at,
                            'completed_by' => $objectiveStaff->completed_by,
                            'approved_at' => $objectiveStaff->approved_at,
                            'approved_by' => $objectiveStaff->approved_by,
                            'okr_point' => $objectiveStaff->okr_point,
                            'remark' => $objectiveStaff->remark,
                            'repetition_count' => $objectiveStaff->repetition_count,
                            'completed_objective_keys' => isset($objectiveStaff->completedObjectiveKeys) ? $objectiveStaff->completedObjectiveKeys->map(function($completedObjectiveKey) {
                                return [
                                    'id' => $completedObjectiveKey->id,
                                    'objective_key_id' => $completedObjectiveKey->objective_key_id,
                                    'objective_staff_id' => $completedObjectiveKey->objective_staff_id,
                                ];
                            }):[],
                        ];
                    });
                })
                : [],
        ];
    }
}
