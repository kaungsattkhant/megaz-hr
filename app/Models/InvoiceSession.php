<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceSession extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'start_date_time',
        'end_date_time',
        'total_session',
        'total_session_duration',
        'total_session_price',
        'session_unit_price',
        'discount_session',
        'discount_session_price',
        'discount_id',
        'change_room_order',
        'invoice_id',
        'entity_id',
        'is_active',
    ];
    public function roomSessions(){
        return $this->hasMany(RoomSession::class);
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
    public function entity(){
        return $this->belongsTo(Entity::class);
    }
}
