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
            'old_meeting'=>new MeetingMinuteListResource($this->old_meeting),
            'attendances' => $this->whenLoaded('attendances', function () {
                return $this->attendances->map(function ($staff) {
                    return [
                        'id' => $staff->id,
                        'name' => $staff->name,
                    ];
                })->values();
            }),
            'alignments' => $this->whenLoaded('alignments', function () {
                return $this->alignments->map(function ($alignment) {
                    return [
                        'id' => $alignment->id,
                        'name' => $alignment->name,
                        'remark'=>$alignment->pivot->remark,
                    ];
                })->values();
            }),
            'kpi_snapshots' => $this->whenLoaded('kpiSnapshots', function () {
                return $this->kpiSnapshots->map(function ($kpiSnapshot) {
                    return [
                        'id' => $kpiSnapshot->id,
                        'name' => $kpiSnapshot->name,
                        'value' => $kpiSnapshot->pivot->value,
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
                            'okr_point' => $instruction->objective ? $instruction->objective->okr_point : null,
                            'tag' => $instruction->objective_staff ? $instruction->objective->objective_staff : null,
                            'start_date' => $instruction->objective_staff ? $instruction->objective_staff->start_date : null,
                            'due_date' => $instruction->objective_staff ? $instruction->objective_staff->due_date : null,
                            'remark' => $instruction->objective_staff ? $instruction->objective_staff->remark : null,
                            'accountable' => $instruction->objective && $instruction->objective->accountable ? [
                                'id' => $instruction->objective->accountable->id,
                                'name' => $instruction->objective->accountable->name,
                            ] : null,
                            'consulted' => $instruction->objective && $instruction->objective->consulted ? [
                                'id' => $instruction->objective->consulted->id,
                                'name' => $instruction->objective->consulted->name,
                            ] : null,
                            'informed' => $instruction->objective && $instruction->objective->informed ? [
                                'id' => $instruction->objective->informed->id,
                                'name' => $instruction->objective->informed->name,
                            ] : null,
                            'responsible' => $instruction->responsible ? [
                                'id' => $instruction->responsible->id,
                                'name' => $instruction->responsible->name,
                            ] : null,
                        ],
                        'project' => $instruction->project ? [
                            'id' => $instruction->project->id,
                            'name' => $instruction->project->name,
                        ] : null,
                     
                        // 'priority' => $instruction->priority,g
                        // 'assigned' => $instruction->assignedTo ? [
                        //     'id' => $instruction->assignedTo->id,
                        //     'name' => $instruction->assignedTo->name,
                        // ] : null,
                        // 'responsible' => $instruction->responsible ? [
                        //     'id' => $instruction->responsible->id,
                        //     'name' => $instruction->responsible->name,
                        // ] : null,
                        
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
