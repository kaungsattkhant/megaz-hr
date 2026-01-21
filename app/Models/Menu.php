<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Item;
use App\Models\MenuCategory;
use App\Models\MenuPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'menu_category_id',
        'code',
        'image_url',
        'image_path',
        'is_active',
        'is_feature',
        'description'
    ];

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
        return $this->hasOne(MenuPrice::class)->latestOfMany();
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

    // public function areas()
    // {
    //     return $this->belongsToMany(Area::class, 'menu_area');
    // }

    public function menuPlaces()
    {
        return $this->belongsToMany(CookingPlace::class, 'menu_places');
    }

    public function cookingAreas()
    {
        return $this->belongsToMany(CookingPlace::class, 'menu_areas', 'menu_category_area_id', 'cooking_area_id')
            ->withTimestamps();
    }

    public function availableCookingPlaces()
    {
        return $this->morphMany(AvailableCookingPlace::class, 'cooking_placeable');
    }


    public function menuSteps(): HasMany
    {
        return $this->hasMany(MenuStep::class);
    }

    public function subMenus()
    {
        return $this->belongsToMany(Menu::class, 'sub_menus', 'menu_id', 'sub_menu_id');
    }


    public function scopeMenuFilter($query, $search = null, $price = null, $category = null, $code = null)
    {
        return $query
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('menu_category', function ($catQuery) use ($search) {
                            $catQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhere('code', 'like', '%' . $search . '%')
                        ->orWhereHas('price', function ($subQuery) use ($search) {
                            $subQuery->where('price', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($price, function ($q) use ($price) {
                $q->whereHas('price', function ($subQuery) use ($price) {
                    $subQuery->where('price', $price);
                });
            })
            ->when($category, function ($q) use ($category) {
                $q->whereHas('menu_category', function ($catQuery) use ($category) {
                    $catQuery->where('id', $category);
                });
            })
            ->when($code, function ($q) use ($code) {
                $q->where('code', 'like', '%' . $code . '%');
            });
    }


    public function targetMrpForecasts()
    {
        return $this->morphMany(TargetMrpForecast::class, 'mrp_forecastable');
    }
}
