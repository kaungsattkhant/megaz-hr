<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Menu;

class MenuCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'image_path', 'image_url'];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
