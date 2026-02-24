<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectInstructionResource extends JsonResource
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
            'meeting_minute_id' => $this->meeting_minute_id,
            'project_id' => $this->project_id,
            'tag' => $this->tag,
            'priority' => $this->priority,
            'objective' => $this->whenLoaded('objective', function () {
                return [
                    'id' => $this->objective->id,
                    'name' => $this->objective->objective_name,
                ];
            }),
            'objective_keys' => $this->whenLoaded('objectiveKeys', function () {
                return $this->objectiveKeys->map(function ($key) {
                    return [
                        'id' => $key->id,
                        'name' => $key->name,
                    ];
                })->values();
            }),
            'assigned_to' => $this->assigned_to,
            'responsible_id' => $this->responsible_id,
            'accountable_id' => $this->accountable_id,
            'consulted_id' => $this->consulted_id,
            'informed_id' => $this->informed_id,
            'assigned' => $this->whenLoaded('assignedTo', function () {
                return [
                    'id' => $this->assignedTo->id,
                    'name' => $this->assignedTo->name,
                ];
            }),
            'responsible' => $this->whenLoaded('responsible', function () {
                return [
                    'id' => $this->responsible->id,
                    'name' => $this->responsible->name,
                ];
            }),
            'accountable' => $this->whenLoaded('accountable', function () {
                return [
                    'id' => $this->accountable->id,
                    'name' => $this->accountable->name,
                ];
            }),
            'consulted' => $this->whenLoaded('consulted', function () {
                return [
                    'id' => $this->consulted->id,
                    'name' => $this->consulted->name,
                ];
            }),
            'informed' => $this->whenLoaded('informed', function () {
                return [
                    'id' => $this->informed->id,
                    'name' => $this->informed->name,
                ];
            }),
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
            'remark' => $this->remark,
            'okr_point' => $this->okr_point,
        ];
    }
}
