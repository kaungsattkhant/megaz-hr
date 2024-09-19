<?php

namespace App\Repositories\Task;

use App\Models\Staff;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\TaskImage;
use App\Traits\TaskAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskRepository implements TaskRepositoryInterface
{
    use TaskAssign;
    public function getTasksOfRolesFromArea($areaId)
    {

        $dayName = now()->format('D');
        $role_id = UserData()->roles[0]->id;
        //create task detail for staff
        $this->createTaskDetailForStaff($role_id);
        $tasks = TaskDetail::orderBy('task_details.id', 'desc')
            ->join('tasks', 'task_details.task_id', 'tasks.id')
            ->where('tasks.assigned_days', 'like', "%{$dayName}%")
            ->where('task_details.staff_id', UserData()->id)
            ->when($areaId != null || $areaId != "null", function ($q) use ($areaId) {
                $q->where('tasks.area_id', $areaId);
            })
            ->where('is_active', 1)
            ->select('task_details.id', 'task_details.date_time', 'task_details.completed_at', 'task_details.is_double_checked', 'task_details.double_checked_by', 'task_details.double_checked_at', 'task_details.status',
                'tasks.area_id', 'tasks.role_id', 'tasks.name', 'tasks.description', 'tasks.assigned_days', 'tasks.start_date', 'tasks.due_date', 'tasks.type')
            ->paginate(20);
        return $tasks;

    }

    public function updateTaskStatus(array $data, int $id)
    {
        $taskDetail = TaskDetail::find($id);
        if ($taskDetail) {
            $taskDetail->update($data);
            return $taskDetail;
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
            $tasks = Task::where('is_active', 1)->skip($skip)->take($perPage)->with(['role.department', 'task_details','task_details.completedBy','task_details.doubleCheckedBy'])->get();
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
        $data['area_id'] = $data['area_id'] == null || $data['area_id'] == "null" ? null : $data['area_id'];
        DB::beginTransaction();
        try {
            // foreach ($staffs as $staff) {
            $tasks[] = [
                'kpi' => $data['kpi'],
                'area_id' => $data['area_id'],
                'role_id' => $data['role_id'],
                'name' => $data['name'],
                'description' => $data['description'],
                'assigned_days' => $data['assigned_days'],
                'created_by' => UserData()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // }
            Task::insert($tasks);
            DB::commit();
            ResponseMessage('Task create successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

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
        $staff=Staff::find($id);
        $role_id=$staff->roles[0]->id;
        $this->createTaskDetailForStaff($role_id);
        $tasks = TaskDetail::join('tasks', 'task_details.task_id', 'tasks.id')
            ->where('staff_id', $id)
            ->where('is_active', 1)
            ->select('task_details.id', 'task_details.date_time', 'task_details.completed_at', 'task_details.is_double_checked', 'task_details.double_checked_by', 'task_details.double_checked_at', 'task_details.status',
                'tasks.area_id', 'tasks.role_id', 'tasks.name', 'tasks.description', 'tasks.assigned_days', 'tasks.start_date', 'tasks.due_date', 'tasks.type')
            ->paginate(20);
        return $tasks;
    }

    public function doubleCheckTasks(int $id, string $status)
    {
        $staff = UserData();
        DB::beginTransaction();
        try {
            if ($staff->checkRoles(['Supervisor'])) {
                $taskDetail = TaskDetail::find($id);
                if ($taskDetail->is_double_checked == 1) {
                    ResponseMessage('Task is already double checked');
                }

                if ($taskDetail->completed_by == null) {
                    ResponseMessage('Please complete task first', 422);
                }
                $taskDetail->double_checked_by = $staff->id;
                $taskDetail->is_double_checked = 1;
                $taskDetail->double_checked_at = CurrentTime();
                $taskDetail->status = $status;
                $taskDetail->save();
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

    public function taskReport($request)
    {
        $departmentId = $request->department_id;
        $date = convertDateFormat($request->date);
        $staffs = Staff::select(['id', 'name'])
            ->when(!is_null($departmentId), function ($q) use ($departmentId) {
                $q->whereHas('roles.department', function ($query) use ($departmentId) {
                    $query->where('id', $departmentId);
                });
            })
            ->with(['task_details' => function ($query) use ($request, $date) {
                $query->select('id', 'staff_id','status', 'double_checked_by', 'created_at','task_id')
                    ->when(isset($request->date) && !is_null($date), function ($q) use ($date) {
                        $q->whereDate('created_at', $date);
                    })
                    ->with(['doubleCheckedBy:id,name','task:id,name,description']);
            }, 'roles'])
            ->has('task_details')
            ->orderBy('id', 'asc')
            ->paginate(20);
        return $staffs;

    }


    public function taskByRoleId(int $roleId)
    {
        $task = Task::where('role_id',$roleId)->get();
        ResponseData($task);
    }


    //  custom task
    public function customTaskCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['type'] = "custom_task";
            $staff = Staff::find($data['staff_id'])->first();
            $role = $staff->roles->first();
            $data['role_id'] = $role->id;
            $data['created_by'] = UserData()->id;
            $data['date_time'] = CurrentTime();
            $task = Task::create($data);
            $data['task_id'] = $task->id;
            $taskDetail = TaskDetail::create($data);
            DB::commit();
            ResponseData($task, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;

        }
    }

    public function customTaskUpdate(Request $request, int $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $task = Task::find($id);
            $data['type'] = "custom_task";
            $staff = Staff::find($data['staff_id'])->first();
            $role = $staff->roles->first();
            $data['role_id'] = $role->id;
            $task->update($data);
            $data['task_id'] = $task->id;
            $taskDetail = TaskDetail::where('task_id',$task->id)->first();
            $taskDetail->update($data);
            DB::commit();
            ResponseData($task, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;

        }
    }

    public function listCustomTasks(Request $request)
    {
        $tasks = Task::where('type', 'custom_task')->with(['customTaskDetail.staff','role.department'])->orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        ResponseData($tasks);
    }

    public function taskCustomDetail(int $id)
    {
        $task = Task::where('type', 'custom_task')->with(['customTaskDetail.staff','role.department'])->find($id);
        if (!$task) {
            ResponseMessage('Task not found', 404);
        }
        ResponseData($task, 200);
    }

    // add images
    public function addTaskImages(int $taskId,Request $request)
    {
        DB::beginTransaction();
        try{
            $data = $request->all();
            $request->validate([
                'task_images.*' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $task = Task::find($taskId);
            $data['task_id'] = $task->id;
            $imageData = $data['task_images'];
            if(!empty($imageData))
            {
                if (is_string($imageData)) {
                    $imageData = json_decode($imageData, true); // Decode only if it's a JSON string
                }
                foreach($imageData as $image)
                {
                    $extension = $image->getClientOriginalExtension();
                    $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                    $data['image_path'] = $image->storeAs('images/task_images', $hashedName, 'public');
                    $data['image_url'] = Storage::url($data['image_path']);
                    $data['task_id'] = $task->id;
                    TaskImage::create($data);
                }
            }

            DB::commit();
            ResponseMessage("Images uploaded successfully",200);

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getTaskImages(int $task_id)
    {
        $task = Task::with('taskImages')->find($task_id);
        ResponseData($task);
    }

    public function deleteTaskImage(int $image_id)
    {
        $taskImage = TaskImage::find($image_id);
        $taskImage->delete();
        ResponseMessage("Image deleted successfully",200);
    }


}
