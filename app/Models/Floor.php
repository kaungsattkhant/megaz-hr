<?php

namespace App\Models;

use App\Models\Place;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
