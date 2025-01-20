<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLeft extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_uom_id',
        'base_uom_quantity',
        'uom_id',
        'uom_quantity',
        'uom_conversion_unit_id',
        'quantity',
        'amount',
        'created_by',
        'item_leftable_id',
        'item_leftable_type'
    ];

    public function itemLeftable()
    {
        return $this->morphTo();
    }
}
