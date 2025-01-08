<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\TimeShift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckIn extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'time_shift_id',
        'check_in_date_time',
        'check_out_date_time',
        'check_in_photo_url',
        'check_in_photo_path',
        'check_out_photo_url',
        'check_out_photo_path',
        'is_current_checked_in'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function timeShift()
    {
        return $this->belongsTo(TimeShift::class, 'time_shift_id');
    }

    public function scopeCheckInFilter($query, $from_date = null, $to_date = null, $staff_id = null)
    {
        return $query
            ->when($from_date, function ($q) use ($from_date) {
                $q->whereDate('check_in_date_time', '>=', $from_date);
            })
            ->when($to_date, function ($q) use ($to_date) {
                $q->whereDate('check_in_date_time', '<=', $to_date);
            })
            ->when($staff_id, function ($q) use ($staff_id) {
                $q->where('staff_id', $staff_id);
            });
    }

    public function scopeDateFilter(Builder $query, $fromDate, $toDate): Builder
    {
        return $query
            ->when($fromDate, function ($q) use ($fromDate) {
                $q->whereDate('check_in_date_time', '>=', $fromDate);
            })
            ->when($toDate, function ($q) use ($toDate) {
                $q->whereDate('check_in_date_time', '<=', $toDate);
            });
    }

    public function scopeStaffFilter(Builder $query, $staffId): Builder
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeStaffNameFilter(Builder $query, $staffName): Builder
    {
        return $query->whereHas('staff', function ($q) use ($staffName) {
            $q->where('name', 'like', '%' . $staffName . '%');
        });
    }

    public function scopeShiftFilter(Builder $query, $shiftId): Builder
    {
        return $query->where('time_shift_id', $shiftId);
    }
}
