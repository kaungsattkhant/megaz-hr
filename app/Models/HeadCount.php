<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadCount extends Model
{
    use HasFactory;

    protected $fillable= [
        'total_head_count',
        'male',
        'child',
        'female'
    ];

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
