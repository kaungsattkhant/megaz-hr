<?php

namespace App\Models;

use App\Models\Objective;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveStaff extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'staff_id',
        'objective_id',
        'start_date',
        'end_date',
        'status',
        'in_progressed_at',
        'in_progressed_by',
        'completed_at',
        'completed_by',
        'approved_at',
        'approved_by',
        'cancelled_at',
        'cancelled_by',
        'okr_point',
        'manager_checked_at',
        'manager_checked_by',
        'remark'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function objStaffImg()
    {
        return $this->hasMany(ObjectiveStaffImage::class);
    }

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id');
    }
}
