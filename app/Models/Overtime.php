<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_date',
        'to_date',
        'staff_id',
        'overtime_category_id',
        'time_shift_id',
        'from_time',
        'to_time',
        'remark',
        'status',
        'created_by',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
