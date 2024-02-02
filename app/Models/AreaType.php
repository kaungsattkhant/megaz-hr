<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Area;

class AreaType extends Model
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
