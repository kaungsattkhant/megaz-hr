<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','from_date','to_date','price','session','is_ktv','is_active','created_by'
    ];

    public function roomSessions()
    {
        return $this->belongsToMany(RoomSession::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(RoomSession::class);
    }

    public function menuPackages()
    {
        return $this->hasMany(MenuPackage::class);
    }
}
