<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'menu_id',
        'quantity',
        'original_price',
        'sub_total_price',
        'discount_value',
        'price',
        'is_foc',
        'order_id',
        'status', 
        'is_complete',
        'menu_service_discount_id',
        'price',
        'remark',
        'area_id',
        'progressed_at',
        'progressed_by',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
        'kitchen_cancelled_at',
        'kitchen_cancelled_by',
        'completed_at',
        'completed_by',
        'placed_at',
        'placed_by',
        'group_order_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
   
}
