<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\StaffEquipmentAssign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function staffEquipmentAssigns()
    {
        return $this->hasMany(StaffEquipmentAssign::class, 'staff_equipment_id');
    }
}
