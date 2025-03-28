<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DayInOffDay extends Model
{
    use HasFactory, SoftDeletes;

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
