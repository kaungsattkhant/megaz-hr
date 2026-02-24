<?php

namespace App\Repositories\MeetingMinute;

use App\Models\Instruction;
use App\Models\Meeting;
use App\Models\MeetingMinute;
use Illuminate\Support\Facades\DB;

class MeetingMinuteRepository implements MeetingMinuteRepositoryInterface
{
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
                if (!empty($instructionData['id'])) {
                    $instruction = Instruction::where('meeting_minute_id', $meetingMinute->id)
                        ->findOrFail($instructionData['id']);
                } else {
                    $instruction = new Instruction();
                    $instruction->meeting_minute_id = $meetingMinute->id;
                }

                $instruction->objective_id = $instructionData['objective_id'];
                $instruction->okr_point = $instructionData['okr_point'] ?? null;
                $instruction->project_id = $instructionData['project_id'];
                $instruction->tag = $instructionData['tag'] ?? null;
                $instruction->assigned_to = $instructionData['assign_to'] ?? ($instructionData['assigned_to'] ?? null);
                $instruction->start_date = $instructionData['start_date'] ?? null;
                $instruction->due_date = $instructionData['due_date'] ?? null;
                $instruction->priority = $instructionData['priority'] ?? null;
                $instruction->reamark = $instructionData['reamark'] ?? ($instructionData['remark'] ?? null);
                $instruction->accountable_id = $instructionData['accountable'] ?? ($instructionData['accountable_id'] ?? null);
                $instruction->consulted_id = $instructionData['consulted_id'] ?? null;
                $instruction->responsible_id = $instructionData['responsible_id'] ?? null;
                $instruction->informed_id = $instructionData['informed_id'] ?? null;
                $instruction->save();

                $keptInstructionIds[] = $instruction->id;
                $instruction->objectiveKeys()->sync($instructionData['instruction_objective_key'] ?? []);
            }

            if (!empty($data['id'])) {
                if (!empty($keptInstructionIds)) {
                    $meetingMinute->instructions()->whereNotIn('id', $keptInstructionIds)->delete();
                } else {
                    $meetingMinute->instructions()->delete();
                }
            }
            DB::commit();
            return $meetingMinute->load(['meeting', 'attendances', 'instructions.objectiveKeys']);
        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function detail($meetingMinute)
    {
        return MeetingMinute::with(['meeting', 'attendances', 'instructions.objectiveKeys'])->findOrFail($meetingMinute->id);
    }

    public function delete($meetingMinute)
    {
        return $meetingMinute->delete();
    }
}
