<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'date','menu_id','quantity','original_price','discount_value','price','order_id','status','is_complete'
    ];
}
