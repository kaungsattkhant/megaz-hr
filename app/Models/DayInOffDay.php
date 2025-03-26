<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayInOffDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'off_day_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function offDay()
    {
        return $this->belongsTo(OffDay::class);
    }
}
