<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookingPlace extends Model
{
    use HasFactory;

    protected $fillable =[
        'name','area_id','created_by'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function availableCookingPlaces()
    {
        return $this->hasMany(AvailableCookingPlace::class,'cooking_place_id');
    }

}
