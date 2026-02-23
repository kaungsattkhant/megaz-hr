<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingMinuteListResource extends JsonResource
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
            'meeting_id' => $this->meeting_id,
            'meeting_minute' => $this->meeting_minute,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'meeting' => $this->whenLoaded('meeting', function () {
                return [
                    'id' => $this->meeting->id,
                    'title' => $this->meeting->title ?? null,
                ];
            }),
            'attendances' => $this->whenLoaded('attendances', function () {
                return $this->attendances->map(function ($staff) {
                    return [
                        'id' => $staff->id,
                        'name' => $staff->name,
                    ];
                })->values();
            }),
            'instructions' => $this->whenLoaded('instructions', function () {
                return $this->instructions->map(function ($instruction) {
                    return [
                        'id' => $instruction->id,
                        'meeting_minute_id' => $instruction->meeting_minute_id,
                        'objective' => [
                            'id' => $instruction->objective_id,
                            'objective_name' => $instruction->objective ? $instruction->objective->objective_name : null,
                        ],
                        'okr_point' => $instruction->okr_point,
                        'project' => $instruction->project ? [
                            'id' => $instruction->project->id,
                            'name' => $instruction->project->name,
                        ] : null,
                        'tag' => $instruction->tag,
                        'assigned' => $instruction->assignedTo ? [
                            'id' => $instruction->assignedTo->id,
                            'name' => $instruction->assignedTo->name,
                        ] : null,
                        'start_date' => $instruction->start_date,
                        'due_date' => $instruction->due_date,
                        'reamark' => $instruction->reamark,
                        'accountable' => $instruction->accountable ? [
                            'id' => $instruction->accountable->id,
                            'name' => $instruction->accountable->name,
                        ] : null,
                        'consulted' => $instruction->consulted ? [
                            'id' => $instruction->consulted->id,
                            'name' => $instruction->consulted->name,
                        ] : null,
                        'informed' => $instruction->informed ? [
                            'id' => $instruction->informed->id,
                            'name' => $instruction->informed->name,
                        ] : null,
                        'instruction_objective_key' => $instruction->relationLoaded('objectiveKeys')
                            ? $instruction->objectiveKeys->map(function ($objectiveKey) {
                                return [
                                    'id' => $objectiveKey->id,
                                    'name' => $objectiveKey->name,
                                ];
                            })->values()
                            : [],
                    ];
                })->values();
            }),
        ];
    }
}
