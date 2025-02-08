<?php

namespace App\Models;

use App\Models\Entity;
use App\Models\Invoice;
use App\Models\EntitySession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'session_duration',
        'invoice_id',
        'price',
        'entity_session_id',
        'discount_session'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function rooms()
    {
        return $this->hasMany(Entity::class,'entity_id');
    }

    public function entitySession()
    {
        return $this->belongsTo(EntitySession::class,'entity_session_id');
    }

}
