<?php

namespace App\Traits;

use App\Models\Task;
use App\Models\Staff;
use App\Models\TaskDetail;
use Illuminate\Support\Facades\DB;

trait TaskAssign
{
    public function createTaskDetailForStaff($role_id)
    {
        $dayName = now()->format('D');
        $now = convertDateFormat(now());
        $checkTask = TaskDetail::whereDate('date_time', $now)
            ->join('tasks', 'task_details.task_id', 'tasks.id')
            ->where('tasks.type', 'task')
            ->where('tasks.role_id',$role_id)
            ->get();
        $taskIds=$checkTask->pluck('task_id')->toArray();
        $inprogressTask = Task::where('assigned_days', 'like', "%{$dayName}%")
            ->where('role_id', $role_id)
            ->where('type', 'task')
            ->whereNotIn('id',$taskIds)
            ->get();
        DB::beginTransaction();
        try {
            $staffs = Staff::staffByRole($role_id);
            if ($inprogressTask->isNotEmpty()) {
                foreach ($inprogressTask as $task) {
                    foreach ($staffs as $staff) {
                        $task = TaskDetail::create([
                            'date_time' => now(),
                            'task_id' => $task->id,
                            'status' => 'assigned',
                            'staff_id' => $staff->id,
                        ]);
                    }

                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
