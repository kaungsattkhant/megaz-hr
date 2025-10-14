<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'cooking_place_id'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
     public function cooking_place(){
        return $this->belongsTo(CookingPlace::class);
    }
}
