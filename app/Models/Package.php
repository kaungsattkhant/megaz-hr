<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','from_date','to_date','price','pay_session','free_session','session','session_price','package_discount','is_ktv','is_active','created_by','image_url','image_path','is_changeable'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Entity::class, 'package_room', 'package_id', 'room_id');
    }

    public function accessories(){
        return $this->hasMany(AccessoryPackage::class);
    }
    public function menuPackages()
    {
        return $this->hasMany(MenuPackage::class);
    }
}
