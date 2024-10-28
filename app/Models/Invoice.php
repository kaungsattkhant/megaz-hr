<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Entity;
use App\Models\Package;
use App\Models\RoomSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'invoice_date',
        'complete_date',
        'created_by',
        'total',
        'tax',
        'sub_total',
        'paid_amount',
        'total_session_price',
        'change',
        'area_id',
        'entity_id',
        'service_charge',
        'head_count_id',
        'payment_status',
        'payment_type',
        'discount_value',
        'customer_id',
        'package_id',
        'room_discount_id',
        'invoice_type',
        'discount_type',
        'order_discount_value',
        'room_discount_value',
        'birthday_discount',
        'customer_level_discount',
        'total_discount',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function room()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function table()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // public function roomAndSessions()
    // {
    //     return $this->hasMany(RoomSession::class);
    // }

    public function roomSession()
    {
        return $this->hasMany(RoomSession::class);
    }

    public function latestSession()
    {
        return $this->hasOne(RoomSession::class)->latest();
    }
}
