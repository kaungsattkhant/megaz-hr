<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'area_id', 'department_id', 'name', 'inventoryable_type', 'inventoryable_id', 'is_active'
    ];

    public function inventoryable()
    {
        return $this->morphTo();
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
}
