<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_category_area_id',
        'cooking_area_id',
        'is_default'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function menuCategoryArea()
    {
        return $this->belongsTo(MenuCategoryArea::class);
    }

    public function cookingArea()
    {
        return $this->belongsTo(Area::class, 'cooking_area_id', 'id');
    }
}
