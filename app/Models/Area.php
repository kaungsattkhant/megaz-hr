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
        'name','area_type_id','is_active','area_category_id','department_id'
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

    public function areaCategory()
    {
        return $this->belongsTo(AreaCategory::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
