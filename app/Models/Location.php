<?php

namespace App\Models;

use App\Models\Floor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
