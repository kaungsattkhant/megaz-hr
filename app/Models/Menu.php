<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Item;
use App\Models\MenuCategory;
use App\Models\MenuPrice;

class Menu extends BaseModel
{
    use HasFactory;

    protected $fillable = ['menu_category_id','name', 'is_active'];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function prices()
    {
        return $this->hasMany(MenuPrice::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot(['weight', 'is_make_pack']);
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }
}
