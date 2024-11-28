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
        'menu_id',
        'quantity'
    ];

    public function mrpForecast()
    {
        return $this->belongsTo(MrpForecast::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
