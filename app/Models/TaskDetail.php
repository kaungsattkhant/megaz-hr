<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    use HasFactory;
    protected $fillable=['date_time','completed_at','completed_by','is_double_checked','double_checked_by','status','task_id'];
}
