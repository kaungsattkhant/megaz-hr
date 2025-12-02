<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Staff;
use App\Models\TimeShift;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffTimeshift extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'staff_id',
        'timeshift_id',
        'area_id',
        'status',
        'confirmed_by',
        'confirmed_at',
        'cancelled_by',
        'cancelled_at',
        'created_by',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function timeshift()
    {
        return $this->belongsTo(TimeShift::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function notification()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
    public function checkIn()
    {
        return $this->hasOne(CheckIn::class, 'time_shift_id', 'timeshift_id')
            ->whereColumn('check_ins.staff_id', 'staff_timeshifts.staff_id')
            ->orderBy('check_in_date_time', 'desc'); // ensures latest check-in first
    }
    public function checkInForStaff($staffId)
    {
        return $this->hasOne(CheckIn::class, 'time_shift_id', 'timeshift_id')
            ->where('staff_id', $staffId)
            ->orderBy('check_in_date_time', 'desc');
    }
}
