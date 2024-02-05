<?php

namespace App\Repositories\Task;

use App\Models\Task;

class TaskRepository implements TaskRepoitoryInterface
{
    public function getTasksOfRolesFromArea($areaId, $roles)
    {
        $tasks = collect();
        foreach($roles as $role){
            $task = Task::where('area_id', $areaId)->where('role_id', $role->id)->first();
            if($task){
                $tasks->push($task);
            }
        }

        return $tasks;
    }
}
