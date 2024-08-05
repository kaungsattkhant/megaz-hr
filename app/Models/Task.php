<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Role;
use App\Models\TaskDetail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'department_id',
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
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get staff of completed_by.
     */
   
}
