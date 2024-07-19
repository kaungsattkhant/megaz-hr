<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodOrderItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'food_order_id','menu_id','area_id','quantity','discount_price','original_price','menu_service_discount_id','status'
    ];
}
