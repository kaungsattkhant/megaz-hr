<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'role_id',
        'staff_id',
        'participantable_id',
        'participantable_type'
    ];
}
