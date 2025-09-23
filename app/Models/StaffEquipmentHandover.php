<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffEquipmentHandover extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_staff_id',
        'to_staff_id',
        'staff_timeshift_id',
        'handover_date',
        'handover_note',
        'status',
        'created_at',
        'created_by',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
    ];

    public function staffEquipmentHandoverItems()
    {
        return $this->hasMany(StaffEquipmentHandoverItem::class);
    }

    public function fromStaff()
    {
        return $this->belongsTo(Staff::class, 'from_staff_id');
    }

    public function toStaff()
    {
        return $this->belongsTo(Staff::class, 'to_staff_id');
    }

    public function staffTimeshift()
    {
        return $this->belongsTo(StaffTimeshift::class, 'staff_timeshift_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(Staff::class, 'cancelled_by');
    }

    public function notification()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
