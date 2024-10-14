<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Item;
use App\Models\MenuCategory;
use App\Models\MenuPrice;
use Carbon\Carbon;

class Menu extends BaseModel
{
    use HasFactory;

    protected $fillable = ['code','menu_category_id', 'name', 'is_active', 'image_path', 'image_url', 'is_feature','description'];

    public function menu_category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function prices()
    {
        return $this->hasMany(MenuPrice::class);
    }

    public function price()
    {
        return $this->hasOne(MenuPrice::class)->orderBy('id', 'desc');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot(['weight', 'price', 'is_make_pack', 'uom_id']);
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }


    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    public function menuServiceDiscounts()
    {
        return $this->morphMany(MenuServiceDiscount::class, 'discountable');
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class,'menu_area');
    }

    public function availableCookingPlaces()
    {
        return $this->morphMany(AvailableCookingPlace::class, 'cooking_placeable');
    }

}
