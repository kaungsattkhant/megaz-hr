<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AreaType;
use App\Models\Inventory;

class Area extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name','area_type_id','is_active'
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function areaType()
    {
        return $this->belongsTo(AreaType::class);
    }

    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }
}
