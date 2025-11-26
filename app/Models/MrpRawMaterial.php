<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MrpRawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'mrp_forecast_id',
        'item_id',
        'uom_id',
        'quantity',
        'amount'
    ];


    public function mrpForecast()
    {
        return $this->belongsTo(MrpForecast::class, 'mrp_forecast_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
}
