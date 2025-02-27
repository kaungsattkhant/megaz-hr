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
        'cooking_area_id'
    ];

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function sellingArea()
    {
        return $this->belongsTo(Area::class, 'area_category_id');
    }

    public function cookingArea()
    {
        return $this->belongsTo(Area::class, 'department_id');
    }
}
