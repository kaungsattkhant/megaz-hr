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

    public function updateTaskStatus(array $data, int $id)
    {
        $task = Task::find($id);
        if($task){
            $task->update($data);

            return $task;
        }
        else{
            return null;
        }
    }

    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Task::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $tasks = Task::where('is_active', 1)->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'tasks');
            $paginationData['tasks'] = $tasks;

            return $paginationData;
        }
        else{
            $tasks = Task::where('is_active', 1)->get();

            return $tasks;
        }
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
