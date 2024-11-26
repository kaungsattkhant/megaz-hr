<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectivekeyStaff extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'staff_id',
        'objective_key_id',
        'status',
        'in_progressed_at',
        'in_progressed_by',
        'completed_at',
        'completed_by',
        'approved_at',
        'approved_by',
        'cancelled_at',
        'cancelled_by'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function objectiveKey()
    {
        return $this->belongsTo(ObjectiveKey::class, 'objective_key_id');
    }

    public function objKeyStaffImg()
    {
        return $this->hasMany(ObjectiveKeyStaffImage::class);
    }
}
