<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AreaType;

class Area extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','area_type_id','is_active'
    ];

    public function areaType()
    {
        return $this->belongsTo(AreaType::class);
    }
}
