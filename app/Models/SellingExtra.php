<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingExtra extends Model
{
    //
    protected $fillable = [
        'selling_extra_category_id',
        'item_id',
        'uom_id',
        'quantity',
        'price',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(SellingExtraCategory::class, 'selling_extra_category_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }
}
