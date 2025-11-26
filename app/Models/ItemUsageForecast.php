<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUsageForecast extends Model
{
    use HasFactory;
    protected $fillable=['date','created_by','department_id'];

    public function forecast_items(){
        return $this->hasMany(\App\Models\ForecastItem::class);
    }

    public function created_by(){
        return $this->belongsTo(\App\Models\Staff::class,'created_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}


