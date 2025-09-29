<?php

namespace App\Models;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Staff;
use App\Models\StaffEquipmentAssign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'uom_id',
        'uom_quantity',
        'quantity',
        'uom_type',
        'type',
        'lost_note',
        'staff_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function equipmentTypeable() : MorphMany
    {
        return $this->morphMany(StaffEquipmentAssign::class, 'equipment_typeable');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
