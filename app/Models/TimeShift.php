<?php

namespace App\Models;

use App\Models\Shift;
use App\Models\CheckIn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'from_time',
        'to_time',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'time_shift_id');
    }
}
