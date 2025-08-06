<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable =[
        'order_id','total_quantity','date','total', 'foc_total',
        'order_sub_total',
        'total_discount_price',
        'invoice_id','is_complete','total_extra_price',
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
