<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Objective;
use App\Models\ObjectiveStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveAssign extends Model
{
    use HasFactory;
    protected $fillable = [
        'objective_id',
        'staff_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function objectiveStaff()
    {
        return $this->hasMany(ObjectiveStaff::class, 'objective_assign_id');
    }
}
