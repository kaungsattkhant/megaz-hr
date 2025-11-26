<?php

namespace App\Models;

use App\Models\Shift;
use App\Models\CheckIn;
use App\Models\StaffTimeshift;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'from_time',
        'to_time',
        'gps_id',
        'is_active',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function gps()
    {
        return $this->belongsTo(Gps::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'time_shift_id');
    }


    public function getShiftHoursAttribute()
    {
        if ($this->from_time && $this->to_time) {

            $from = Carbon::parse($this->from_time);
            $to = Carbon::parse($this->to_time);
            return $to->diffInHours($from);
        }
        return 0;
    }


    public static function calculateTotalShiftDuration()
    {
        $timeShifts = self::whereNotNull('from_time')
            ->whereNotNull('to_time')->get();
        $totalMinutes = 0;

        foreach ($timeShifts as $timeShift) {
            $from = Carbon::parse($timeShift['from_time']);
            $to = Carbon::parse($timeShift['to_time']);
            $totalMinutes += $to->diffInMinutes($from);
        }

        $totalHours = $totalMinutes / 60;

        return round($totalHours, 2);
    }

    public function staffTimeshifts()
    {
        return $this->hasMany(StaffTimeshift::class, 'timeshift_id');
    }
}
