<?php

namespace App\Models;

use App\Models\Objective;
use App\Models\Notification;
use App\Models\ObjectiveAssign;
use App\Models\CompletedObjectiveKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveStaff extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'objective_assign_id',
        'start_date',
        'end_date',
        'status',
        'remark',
        'repetition_count',
        'plan_at',
        'plan_by',
        'do_at',
        'do_by',
        'done_at',
        'done_by',
        'completed_at',
        'completed_by',
        'rejected_at',
        'rejected_by',
        'check_at',
        'check_by',
        'act_at',
        'act_by',
        'okr_point',
        'stage',
        'created_by',
        'cancelled_at',
        'cancelled_by',
        
        // 'assigned_at',
        // 'in_progressed_at',
        // 'in_progressed_by',
        // 'completed_at',
        // 'completed_by',
        // 'approved_at',
        // 'approved_by',
        // 'manager_checked_at',
        // 'manager_checked_by',

    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function objStaffImg()
    {
        return $this->hasMany(ObjectiveStaffImage::class);
    }

    public function objectiveAssign()
    {
        return $this->belongsTo(ObjectiveAssign::class, 'objective_assign_id');
    }
    public function completedObjectiveKeys()
    {
        return $this->hasMany(CompletedObjectiveKey::class, 'objective_staff_id');
    }
    public function notification()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
