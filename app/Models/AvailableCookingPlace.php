<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableCookingPlace extends Model
{
    use HasFactory;

    protected $fillable =[
        'cooking_place_id','cooking_placeable_type','cooking_placeable_id'
    ];

    public function cookingPlace()
    {
        return $this->belongsTo(CookingPlace::class);
    }

    public function cookingPlaceable()
    {
        return $this->morphTo();
    }

}
