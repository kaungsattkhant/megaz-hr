<?php

namespace App\Repositories\Task;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasksOfRolesFromArea($areaId, $roles)
    {
        $tasks = collect();
        foreach ($roles as $role) {
            $task = Task::where('area_id', $areaId)->where('role_id', $role->id)->first();
            if ($task) {
                $tasks->push($task);
            }
        }

        return $tasks;
    }

    public function listAllData()
    {
        $tasks = Task::all();
        return $tasks;
    }

    public function createData(array $data)
    {
        $stringDays = '';
        foreach ($data['assigned_days'] as $day) {
            $stringDays .= $day . ' ';
        }
        $data['assigned_days'] = $stringDays;
        $task = Task::create($data);
        return $task;
    }

    public function updateData(array $data, string $id)
    {

        if ($id) {
            $stringDays = '';
            $task = Task::find($id);
            $task->assigned_days = '';
            $task->save();

            foreach ($data['assigned_days'] as $day) {
                $stringDays .= $day . ' ';
            }
            $data['assigned_days'] = $stringDays;
            $task->update($data);
            return $task;
        } else {
            $stringDays = '';
            foreach ($data['assigned_days'] as $day) {
                $stringDays .= $day . ' ';
            }
            $data['assigned_days'] = $stringDays;
            $task = Task::create($data);
            return $task;
        }
    }

    public function deleteData($id)
    {
        $task = Task::find($id);
        if(!$task || $task == null)
        {
            return false;
        }
        $task->is_active=0;
        $task->save();
        return true;
    }
}
