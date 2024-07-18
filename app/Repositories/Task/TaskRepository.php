<?php

namespace App\Repositories\Task;

use App\Models\Task;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasksOfRolesFromArea($areaId)
    {
        $dayName = now()->format('D');
        $tasks = Task::
             when($areaId!=null || $areaId!="null",function($q)use($areaId){
                $q->where('area_id',$areaId);
             })
            ->where('assigned_days', 'like', "%{$dayName}%")
            ->where('is_active', 1)
            ->where('staff_id',UserData()->id)
            ->paginate(20);
        return $tasks;
    }

    public function updateTaskStatus(array $data, int $id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->update($data);

            return $task;
        } else {
            return null;
        }
    }

    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Task::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $tasks = Task::where('is_active', 1)->skip($skip)->take($perPage)->with(['role.department','completedBy','doubleCheckedBy'])->get();
            $paginationData = MakePaginationData($request, $totalCount, 'tasks');
            $paginationData['tasks'] = $tasks;

            return $paginationData;
        } else {
            $tasks = Task::where('is_active', 1)->get();

            return $tasks;
        }
    }

    public function createData(array $data)
    {
        $staffs=Staff::staffByRole($data['role_id']);
        $data['area_id']=$data['area_id']==null || $data['area_id']=="null" ? null : $data['area_id'];
        foreach ($staffs as $staff) {   
            $tasks[] = [
                'staff_id' => $staff->id,
                'area_id'  => $data['area_id'],
                'role_id'=>$data['role_id'],
                'name'=>$data['name'],
                'description'=>$data['description'],
                'assigned_days'=>$data['assigned_days'],
                'created_by' => UserData()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Task::insert($tasks);
        ResponseMessage('Task create successfully',200);
    }

    public function updateData(array $data, int $id)
    {
        $task = Task::find($id);
        if ($task) {
            $data = RemoveNullValues($data);
            $task->update($data);
        }
        return $task;
    }

    public function deleteData(int $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return false;
        }
        $task->is_active = 0;
        $task->save();

        return true;
    }

    public function getTasksByStaff(int $id)
    {
        $tasks = Task::where('staff_id', $id)
        ->where('is_active', 1)->paginate(20);
        return $tasks;
    }

    public function doubleCheckTasks(int $id, string $status)
    {
        $staff = UserData();
        DB::beginTransaction();
        try {
            if ($staff->checkRoles(['Supervisor'])) {
                $task = Task::find($id);
                if ($task->is_double_checked == 1) {
                    ResponseMessage('Task is already double checked');
                }

                if($task->completed_by==null)
                {
                    ResponseMessage('Please complete task first',422);
                }
                $task->double_checked_by = $staff->id;
                $task->is_double_checked = 1;
                $task->status = $status;
                $task->save();
                DB::commit();
                ResponseMessage('Task double checked done');
            } else {
                ResponseMessage('Permission is not allowed', 403);
            }
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
