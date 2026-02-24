<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectInstructionResource;
use App\Models\Instruction;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query()->orderByDesc('id');

        if ($request->has('per_page') || $request->has('page')) {
            return ResponseData($query->paginate(config('common.list_count')));
        }

        return ResponseData($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:projects,id',
            'name' => 'required|string|max:255',
        ]);

        if (!isset($validated['id'])) {
            $validated['id'] = null;
        }

        $project = Project::updateOrCreate(
            ['id' => $validated['id']],
            $validated
        );

        return ResponseData($project);
    }

    public function getInstructionByProject($projectId, Request $request)
    {
        Project::findOrFail($projectId);

        $instructions = Instruction::with([
            'objective',
            'objectiveKeys',
            'assignedTo',
            'responsible',
            'accountable',
            'consulted',
            'informed',
        ])
            ->where('project_id', $projectId)
            ->orderByRaw('priority IS NULL')
            ->orderBy('priority')
            ->get();

        $groupedData = $instructions
            ->groupBy('tag')
            ->map(function ($tagGroup) use ($request) {
                return ProjectInstructionResource::collection($tagGroup)->resolve($request);
            });

        $tagOrder = ['plan', 'do', 'check', 'act'];
        $groupedData = collect($tagOrder)
            ->filter(fn($tag) => $groupedData->has($tag))
            ->mapWithKeys(fn($tag) => [$tag => $groupedData->get($tag)]);

        return response()->json([
            'data' => $groupedData,
        ]);
    }

}
