<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingExtra extends Model
{
    //
    protected $fillable = [
        'item_id',
        'uom_id',
        'quantity',
        'price',
        'is_active'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }
}
