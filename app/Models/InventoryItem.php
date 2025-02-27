<?php

namespace App\Models;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'item_id',
        'base_uom_id',
        'base_uom_min_quantity',
        'uom_id',
        'uom_min_quantity',
        'min_quantity'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function baseUom()
    {
        return $this->belongsTo(Uom::class, 'base_uom_id');
    }
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
}
