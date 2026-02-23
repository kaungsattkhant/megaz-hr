<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

}
