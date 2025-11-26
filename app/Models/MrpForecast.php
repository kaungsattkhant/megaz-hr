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

    public function targetMrpForecasts()
    {
        return $this->hasMany(TargetMrpForecast::class, 'mrp_forecast_id');
    }

    public function MrpHrs()
    {
        return $this->hasMany(MrpHr::class, 'mrp_forecast_id');
    }

    public function MrpRawMaterials()
    {
        return $this->hasMany(MrpRawMaterial::class, 'mrp_forecast_id');
    }
}
