<?php

namespace App\Models;

use App\Models\AreaType;
use App\Models\Inventory;

use App\Models\StaffTimeshift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area_type_id',
        'is_active',
        'area_category_id',
        'department_id',
        'is_pos',
        'type',
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

    public function menuCategories()
    {
        return $this->belongsToMany(MenuCategory::class, 'menu_category_areas', 'selling_area_id', 'menu_category_id')->withPivot('id');
    }

    public function staffTimeshifts()
    {
        return $this->hasMany(StaffTimeshift::class, 'area_id');
    }

    public function inventoryable()
    {
        return $this->morphOne(Inventoryable::class, 'inventoryable');
    }

    public function inventory()
    {
        return $this->morphOne(Inventoryable::class, 'inventoryable')
            ->with('inventory');
    }
}
