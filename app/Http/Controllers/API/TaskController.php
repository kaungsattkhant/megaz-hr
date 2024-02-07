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
        $roles = $staff->roles()->select('id')->get();
        $roleIds = [];
        foreach($roles as $role){
            array_push($roleIds, $role->id);
        }

        if(count($roleIds) < 1){
            ResponseMessage('Unauthorized, no role found', 401);
        }

        $tasks = $this->taskRepo->getTasksOfRolesFromArea($areaId, $roleIds);

        ResponseData($tasks);
    }

    public function getTaskData(Request $request)
    {
        $tasks = $this->taskRepo->listAllData($request);

        ResponseData($tasks);
    }

    public function createTask(TaskCreateRequest $request)
    {
        $task = $this->taskRepo->createData($request->all());

        ResponseData($task);
    }

    public function updateTask(TaskUpdateRequest $request, $id)
    {
        $task = $this->taskRepo->updateData($request->all(), $id);
        if(!$task){
            ResponseMessage('No task found with given id', 404);
        }

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
