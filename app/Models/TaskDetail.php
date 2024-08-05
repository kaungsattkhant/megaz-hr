<?php

namespace App\Models;

use App\Models\Task;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskDetail extends Model
{
    use HasFactory;
    protected $fillable=['date_time','completed_at','completed_by','is_double_checked','double_checked_by','status','task_id','staff_id'];

    public function task(){
        return $this->belongsTo(Task::class,'task_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(Staff::class, 'completed_by');
    }

    public function doubleCheckedBy()
    {
        return $this->belongsTo(Staff::class, 'double_checked_by');
    }
}
