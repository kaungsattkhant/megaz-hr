<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategoryArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_category_id',
        'selling_area_id',
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function sellingArea()
    {
        return $this->belongsTo(Area::class, 'selling_area_id');
    }

    public function menuAreas()
    {
        return $this->hasMany(MenuArea::class);
    }
}
