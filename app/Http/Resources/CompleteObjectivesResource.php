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
                        'is_done' => $key->is_done,
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
                            // 'approved_at' => $staff->approved_at,
                            // 'approved_by' => $staff->approved_by,
                            // 'cancelled_at' => $staff->cancelled_at,
                            // 'cancelled_by' => $staff->cancelled_by,
                            'okr_point' => $objectiveStaff->okr_point,
                            'remark' => $objectiveStaff->remark,
                            'repetition_count' => $objectiveStaff->repetition_count,
                        ];
                    });
                })
                : [],
        ];
    }
}
