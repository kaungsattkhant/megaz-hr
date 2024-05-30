<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomSession extends Model
{
    use HasFactory;

    protected $fillable=[
        'start_date','end_date','session_duration','invoice_id','price','entity_id'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function rooms()
    {
        return $this->hasMany(Entity::class,'entity_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }
}
