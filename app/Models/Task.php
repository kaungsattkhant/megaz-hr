<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Role;
use App\Models\TaskDetail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Psy\Readline\Hoa\_Protocol;

class Task extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'role_id', 'area_id',
        'name','description',
        'assigned_days',
        'completed_at',
        'completed_by',
        'is_double_checked',
        'double_checked_by',
        'created_by',
        'status','is_active',
        'type',
        'kpi',
        'start_date',
        'due_date'
    ];

    public function task_details(){
        return $this->hasMany(TaskDetail::class);
    }

    public function customTaskDetail()
    {
        return $this->hasOne(TaskDetail::class)
                    ->latest('created_at');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function duties()
    {
        return $this->belongsToMany(Duty::class,'duty_task');
    }

    public function taskImages()
    {
        return $this->hasMany(TaskImage::class);
    }

    /**
     * Get staff of completed_by.
     */

}
