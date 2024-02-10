<?php

namespace App\Repositories\Task;

use Illuminate\Http\Request;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasksOfRolesFromArea(int $areaId, array $roleIds)
    {
        $dayName = now()->format('D');
        $tasks = Task::where('area_id', $areaId)->whereIn('role_id', $roleIds)
        ->where('assigned_days', 'like', "%{$dayName}%")
        ->where('is_active', 1)
        ->get();

        return $tasks;
    }

    public function listAllData(Request $request)
    {
        $tasks = Task::with('role.department')->get();
        $tasksData = Pagination($tasks, $request, 'tasks');

        return $tasksData;
    }

    public function createData(array $data)
    {
        $task = Task::create($data);

        return $task;
    }

    public function updateData(array $data, int $id)
    {
        $task = Task::find($id);
        if($task){
            $data = RemoveNullValues($data);
            $task->update($data);
        }

        return $task;
    }

    public function deleteData(int $id)
    {
        $task = Task::find($id);
        if(!$task)
        {
            return false;
        }
        $task->is_active=0;
        $task->save();

        return true;
    }
}
