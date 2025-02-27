<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Scopes\IsActiveScope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'image_path',
        'image_url'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function areas()
    {
        return $this->hasManyThrough(
            Area::class,
            CookingPlace::class,
            'area_id',
            'id',
            'id',
            'area_id'
        )->distinct();
    }
}
