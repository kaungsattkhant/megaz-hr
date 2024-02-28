<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id','invoice_date','complete_date','created_by','total','tax','sub_total','paid_amount','total_session_price','change','area_id','entity_id','service_id','head_count_id','payment_status','payment_type','discount_value','customer_id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function roomAndSessions()
    {
        return $this->hasMany(RoomSession::class);
    }

    public function service()
    {
        return $this->belongsTo(Entity::class, 'service_id')->where('entity_type', 'service');
    }

    // public function service


}
