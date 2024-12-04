<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MrpForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'date',
    ];

    public function targetMrpForecast()
    {
        return $this->hasMany(TargetMrpForecast::class);
    }
}
