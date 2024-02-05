<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\Task\TaskRepoitoryInterface;

class TaskController extends Controller
{
    //
    private $taskRepo;

    public function __construct(TaskRepoitoryInterface $repo)
    {
        $this->taskRepo = $repo;
    }

    public function getTasksOfRolesFromArea(Request $request, int $areaId)
    {
        $staff = $request->user();
        $roles = $staff->roles;
        $tasks = $this->taskRepo->getTasksOfRolesFromArea($areaId, $roles);

        ResponseData($tasks);
    }
}
