<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'from_date',
        'to_date',
        'place',
        'chaired_by',
        'description',
        'created_by',
        'meeting_type'
    ];
}
