<?php

namespace App\Models;

use App\Models\StaffEquipmentAssign;
use App\Models\StaffEquipmentHandover;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffEquipmentHandoverItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_equipment_handover_id',
        'item_id',
        'uom_id',
        'uom_quantity',
        'quantity',
        'uom_type',
        'type'
    ];

    public function staffEquipmentHandover()
    {
        return $this->belongsTo(StaffEquipmentHandover::class, 'staff_equipment_handover_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
}
