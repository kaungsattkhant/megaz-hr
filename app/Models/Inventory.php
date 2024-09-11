<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'area_id', 'department_id', 'name', 'is_active','start_time','end_time'
    ];


    public function inventoryable()
    {
        return $this->hasMany(Inventoryable::class);
    }

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function inventory_ledgers()
    {
        return $this->hasMany(InventoryLedger::class);
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class,'inventory_staff', 'inventory_id', 'staff_id');
    }
}
