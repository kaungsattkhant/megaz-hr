<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffDayRequest extends Model
{
    //
    protected $fillable = [
        'staff_timeshift_id',
        'type',
        'original_timeshift_id',
        'change_timeshift_id',
        'remark',
        'status',
        'handled_by',
        'handled_at'
    ];

    public function staffTimeshift()
    {
        return $this->belongsTo(StaffTimeshift::class);
    }

    public function originalShift()
    {
        return $this->belongsTo(TimeShift::class, 'original_timeshift_id');
    }

    public function changeShift()
    {
        return $this->belongsTo(TimeShift::class, 'change_timeshift_id');
    }

    public function handledBy()
    {
        return $this->belongsTo(Staff::class, 'handled_by');
    }
}
