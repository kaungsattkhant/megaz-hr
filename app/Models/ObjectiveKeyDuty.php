<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectiveKeyDuty extends Model
{
    use HasFactory;

    protected $fillable = [
        'assign_date',
        'created_by',
        'is_active',
    ];

    public function objectivekeyStaff()
    {
        return $this->hasMany(ObjectivekeyStaff::class, 'objective_key_duty_id');
    }
}
