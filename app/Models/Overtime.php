<?php

namespace App\Models;

use App\Models\TimeShift;
use App\Models\OvertimeCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_date',
        'to_date',
        'staff_id',
        'overtime_category_id',
        'time_shift_id',
        'from_time',
        'to_time',
        'remark',
        'status',
        'created_by',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
    ];
    protected $hidden = [
        'created_by',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
        'created_at',
        'updated_at',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function timeShift()
    {
        return $this->belongsTo(TimeShift::class);
    }
    public function overtimeCategory()
    {
        return $this->belongsTo(OvertimeCategory::class);
    }
}
