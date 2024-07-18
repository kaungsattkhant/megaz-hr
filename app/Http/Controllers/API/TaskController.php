<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskUpdateRequest;

use App\Models\Staff;

use App\Repositories\Task\TaskRepositoryInterface;

class TaskController extends Controller
{
    //
    private $taskRepo;

    public function __construct(TaskRepositoryInterface $repo)
    {
        $this->taskRepo = $repo;
    }

    public function getTaskList(Request $request)
    {
        // dd($request->area_id);
        $staff = $request->user();
        $roles = $staff->roles()->select('id')->get();
        $roleIds = [];
        foreach($roles as $role){
            array_push($roleIds, $role->id);
        }

        if(count($roleIds) < 1){
            ResponseMessage('Unauthorized, no role found', 401);
        }

        $tasks = $this->taskRepo->getTasksOfRolesFromArea($request->area_id);
        if(count($tasks) < 1){
            ResponseMessage('No tasks found', 404);
        }

        ResponseData($tasks);
    }

    public function updateTaskStatus(Request $request, int $taskId)
    {
        $data['completed_at'] = CurrentTime();
        $data['completed_by'] = $request->user()->id;
        $data['status'] = $request->status;
        $task = $this->taskRepo->updateTaskStatus($data, $taskId);
        if(!$task){
            ResponseMessage('No task found with the given id', 404);
        }
        ResponseMessage('Task status updated');
        ResponseData($task);
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

    public function getStaffTasksBySupervisor(Request $request, int $staffId)
    {
        $staff = Staff::find($request->user()->id);
        $roles = $staff->roles;
        $isASupervisor = false;
        foreach($roles as $role){
            if($role->name == 'Supervisor'){
                $isASupervisor = true;
                break;
            }
        }
        if(!$isASupervisor){
            ResponseMessage('Not a supervisor', 403);
        }

        $tasks = $this->taskRepo->getTasksByStaff($staffId);
        ResponseData($tasks);
    }

    public function taskDoubleChecked(Request $request, $id)
    {
        $task = $this->taskRepo->doubleCheckTasks($id, $request->status);
    }
}
