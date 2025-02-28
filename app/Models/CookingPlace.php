<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookingPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area_id',
        'created_by'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function availableCookingPlaces()
    {
        return $this->hasMany(AvailableCookingPlace::class, 'cooking_place_id');
    }

    // public function menus()
    // {
    //     return $this->belongsToMany(Menu::class);
    // }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_areas', 'cooking_area_id', 'menu_category_area_id')
            ->withTimestamps();
    }
}
