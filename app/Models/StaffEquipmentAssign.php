<?php

namespace App\Models;

use App\Models\StaffEquipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffEquipmentAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_equipment_id',
        'item_id',
        'uom_id',
        'uom_quantity',
        'quantity',
        'uom_type'
    ];

    public function staffEquipment()
    {
        return $this->belongsTo(StaffEquipment::class, 'staff_equipment_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // public function baseUom()
    // {
    //     return $this->belongsTo(Uom::class, 'base_uom_id');
    // }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
}
