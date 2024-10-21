<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntitySession extends Model
{
    use HasFactory;

    protected $fillable =[
        'start_time','end_time','is_available','is_active','entity_id',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function roomSessions()
    {
        return $this->hasMany(RoomSession::class);
    }

    public function roomSession()
    {
        return $this->hasOne(RoomSession::class)->latestOfMany();
    }



}
