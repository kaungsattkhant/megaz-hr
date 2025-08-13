<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Staff;
use App\Models\TimeShift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffTimeshift extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'staff_id',
        'timeshift_id',
        'area_id',
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
}
