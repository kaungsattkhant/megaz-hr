<?php

namespace App\Models;

use App\Models\TimeShift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function timeShifts()
    {
        return $this->hasMany(TimeShift::class);
    }
}
