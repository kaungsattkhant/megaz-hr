<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Repositories\Task\TaskRepositoryInterface;

class TaskController extends Controller
{
    //
    private $taskRepo;

    public function __construct(TaskRepositoryInterface $repo)
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

    public function getTaskData()
    {
        $tasks = $this->taskRepo->listAllData();
        ResponseData($tasks);
    }

    public function createTask(TaskCreateRequest $request)
    {
        $task = $this->taskRepo->createData($request->all());
        ResponseData($task);
    }

    public function updateTask(TaskUpdateRequest $request,$id)
    {
        $task = $this->taskRepo->updateData($request->all(),$id);
        ResponseData($task);
    }

    public function deleteTask($id)
    {
        $task = $this->taskRepo->deleteData($id);
        if($task==true)
        {
            ResponseMessage('Task deleted');
        }else{
            ResponseMessage('Task not found or some error occur');
        }
    }
}
