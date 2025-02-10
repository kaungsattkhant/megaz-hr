<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function participantable()
    {
        return $this->morphTo();
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
