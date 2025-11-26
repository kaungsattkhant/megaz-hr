<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffEmergencyContact extends Model
{
    protected $fillable = [
        'staff_id',
        'primary_name',
        'primary_phone',
        'primary_relationship',
        'secondary_name',
        'secondary_phone',
        'secondary_relationship',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
