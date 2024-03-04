<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'service_charge',
        'sub_total',
        'paid_amount',
        'total_session_price',
        'change',
        'area_id',
        'entity_id',
        'head_count_id',
        'payment_status',
        'payment_type',
        'discount_value',
        'customer_id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // public function roomAndSessions()
    // {
    //     return $this->hasMany(RoomSession::class);
    // }

    public function sessions()
    {
        return $this->hasMany(RoomSession::class);
    }

}
