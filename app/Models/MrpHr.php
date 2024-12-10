<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MrpHr extends Model
{
    use HasFactory;

    protected $fillable = [
        'mrp_forecast_id',
        'role_id',
        'total_duration'
    ];

    public function mrpForecast()
    {
        return $this->belongsTo(MrpForecast::class, 'mrp_forecast_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
