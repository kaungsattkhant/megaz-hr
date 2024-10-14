<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable =[
        'order_id','total_quantity','date','total', 'foc_total',
        'total_discount_price',
        'invoice_id','is_complete'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
