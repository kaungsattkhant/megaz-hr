<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodOrder extends Model
{
    use HasFactory;

    protected $fillable=[
        'date_time','customer_id','customer_address_id','delivery_charge','sub_total','total_discount_price','total_price','confirmed_at','confirmed_by','cancelled_at','cancelled_by','status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class);
    }

    public function foodOrderItems()
    {
        return $this->hasMany(FoodOrderItem::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Staff::class,'confirmed_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(Staff::class,'cancelled_by');
    }
}
