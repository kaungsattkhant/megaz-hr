<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForecastItem extends Model
{
    use HasFactory;
    protected $fillable=['item_id','amount','quantity','item_usage_forecast_id'];
    protected $with=['item'];
    public function item(){
        return $this->belongsTo(\App\Models\Item::class);
    }

    public function item_usage_forecast(){
        return $this->belongsTo(\App\Models\ItemUsageForecast::class);
    }
}
