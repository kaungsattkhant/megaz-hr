<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    protected $fillable = [
        'name',
        'role_id',
        'assigned_days',
        'created_by',
        'status',
        'is_active',
    ];
}
