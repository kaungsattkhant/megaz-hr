<?php

namespace App\Repositories\MeetingMinute;

use App\Models\Instruction;
use App\Models\Meeting;
use App\Models\MeetingMinute;
use App\Models\Objective;
use App\Models\ObjectiveAssign;
use App\Models\ObjectiveKey;
use App\Models\ObjectiveStaff;
use Illuminate\Support\Facades\DB;
use App\Http\Action\SendNotification\FcmSendNotification;

class MeetingMinuteRepository implements MeetingMinuteRepositoryInterface
{
    use FcmSendNotification;

    public function list($request)
    {
        $query = MeetingMinute::with(['meeting', 'attendances', 'instructions.objectiveKeys'])->orderByDesc('id');

        if ($request->has('meeting_id')) {
            $query->where('meeting_id', $request->input('meeting_id'));
        }

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('meeting_minute', 'LIKE', '%' . $searchTerm . '%');
        }

        if ($request->has('per_page') || $request->has('page')) {
            return $query->paginate(config('common.list_count'));
        }

        return $query->get();
    }

    public function updateOrCreate(array $data)
    {
        DB::beginTransaction();

        try {
            $meetingMinute = MeetingMinute::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'meeting_id' => $data['meeting_id'],
                    'meeting_minute' => $data['meeting_minute'],
                    // 'is_active' => $data['is_active'] ?? true,
                ]
            );
            $meetingMinute->attendances()->sync($data['attendances'] ?? []);

            $keptInstructionIds = [];



            foreach ($data['instructions'] ?? [] as $instructionData) {
                //create Objective 
                $objective = Objective::create([
                    'objective_name' => $instructionData['objective_name'],
                    'create_by' => \UserData()->id,
                    'okr_point' => $instructionData['okr_point'],
                    'role_id' => $instructionData['role_id'],
                    'sop_id' => $instructionData['sop_id'],
                    'accountable_id' => $instructionData['accountable'],
                    'consulted_id' => $instructionData['consulted_id'],
                    'informed_id' => $instructionData['informed_id'],
                    'responsible_id' => $instructionData['responsible_id'],
                    'priority' => $instructionData['priority'],
                    'project_id'=>$instructionData['project_id'],
                ]);
                $objectiveKeyIds = [];
                foreach ($instructionData['instruction_objective_key'] as $objectiveKey) {
                    $objectiveKey = ObjectiveKey::create(
                        [
                            'objective_id' => $objective->id,
                            'name' => $objectiveKey['name'],
                        ]
                    );
                    $objectiveKeyIds[] = $objectiveKey->id;
                }
                //assign objective 
                $objectiveAssign = ObjectiveAssign::create([
                    'objective_id' => $objective->id,
                    'staff_id' => $instructionData['responsible_id'],
                ]);
                $objectiveStaff = ObjectiveStaff::create(
                    [
                        'start_date' => $instructionData['start_date'],
                        'end_date' => $instructionData['due_date'],
                        'okr_point' => $instructionData['okr_point'],
                        'objective_assign_id' => $objectiveAssign->id,
                    ]
                );
                $notificationData = [
                    'title' => 'OKR Assigned',
                    'preview' => 'A New Okr Assigned to you.',
                ];

                //end objective
                if (!empty($instructionData['id'])) {
                    $instruction = Instruction::where('meeting_minute_id', $meetingMinute->id)
                        ->findOrFail($instructionData['id']);
                } else {
                    $instruction = new Instruction();
                    $instruction->meeting_minute_id = $meetingMinute->id;
                }

                $instruction->objective_id = $objective->id;
                $instruction->okr_point = $instructionData['okr_point'] ?? null;
                $instruction->project_id = $instructionData['project_id'];
                $instruction->tag = $instructionData['tag'] ?? null;
                $instruction->assigned_to = $instructionData['assign_to'] ?? ($instructionData['assigned_to'] ?? null);
                $instruction->start_date = $instructionData['start_date'] ?? null;
                $instruction->due_date = $instructionData['due_date'] ?? null;
                $instruction->priority = $instructionData['priority'] ?? null;
                $instruction->remark = $instructionData['remark'] ?? ($instructionData['remark'] ?? null);
                $instruction->accountable_id = $instructionData['accountable'] ?? ($instructionData['accountable_id'] ?? null);
                $instruction->consulted_id = $instructionData['consulted_id'] ?? null;
                $instruction->responsible_id = $instructionData['responsible_id'] ?? null;
                $instruction->informed_id = $instructionData['informed_id'] ?? null;
                $instruction->save();

                $keptInstructionIds[] = $instruction->id;
                $instruction->objectiveKeys()->sync($objectiveKeyIds);
                // $this->sendFcmNotification($objectiveStaff, $objectiveAssign->staff, $notificationData);
            }

            if (!empty($data['id'])) {
                if (!empty($keptInstructionIds)) {
                    $meetingMinute->instructions()->whereNotIn('id', $keptInstructionIds)->delete();
                } else {
                    $meetingMinute->instructions()->delete();
                }
            }
            // sync alignments pivot (alignment_id, remark)
            if (!empty($data['alignments'])) {
                $alignmentSync = [];
                foreach ($data['alignments'] as $a) {
                    if (empty($a['alignment_id'])) continue;
                    $alignmentSync[$a['alignment_id']] = ['remark' => $a['remark'] ?? null];
                }
                $meetingMinute->alignments()->sync($alignmentSync);
            } else {
                $meetingMinute->alignments()->sync([]);
            }

            // sync kpi snapshots pivot (kpi_snapshot_id, value)
            if (!empty($data['kpi_snapshots'])) {
                $kpiSync = [];
                foreach ($data['kpi_snapshots'] as $k) {
                    if (empty($k['kpi_snapshot_id'])) continue;
                    $kpiSync[$k['kpi_snapshot_id']] = ['value' => $k['value'] ?? null];
                }
                $meetingMinute->kpiSnapshots()->sync($kpiSync);
            } else {
                $meetingMinute->kpiSnapshots()->sync([]);
            }
            DB::commit();
            return $meetingMinute->load(['meeting', 'attendances', 'instructions.objectiveKeys', 'alignments', 'kpiSnapshots']);
        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function detail($meetingMinute)
    {
        return MeetingMinute::with(['meeting', 'attendances', 'instructions.objectiveKeys', 'alignments', 'kpiSnapshots'])->findOrFail($meetingMinute->id);
    }

    public function delete($meetingMinute)
    {
        return $meetingMinute->delete();
    }
}
