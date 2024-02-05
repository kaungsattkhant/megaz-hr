<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Task;

class TaskController extends Controller
{
    //
    public function getTasks(Request $request, int $areaId)
    {
        $staff = $request->user();
        $roles = $staff->roles;
        $tasks = collect();
        foreach($roles as $role){
            $task = Task::where('area_id', $areaId)->where('role_id', $role->id)->first();
            if($task){
                $tasks->push($task);
            }
        }

        ResponseData($tasks);
    }
}
