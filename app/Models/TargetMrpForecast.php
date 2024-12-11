<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetMrpForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'mrp_forecast_id',
        'mrp_forecastable_id',
        'mrp_forecastable_type',
        'quantity',
        'amount',
        'hour',
    ];

    public function mrp_forecastable()
    {
        return $this->morphTo();
    }

    public function mrpForecast()
    {
        return $this->belongsTo(MrpForecast::class, 'mrp_forecast_id');
    }
}
