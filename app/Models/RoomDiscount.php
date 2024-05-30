<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','from_date','to_date','session','free_session','room_id','created_by','is_active'
    ];

    public function room()
    {
        return $this->belongsTo(RoomSession::class);
    }
}
