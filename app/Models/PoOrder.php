<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoOrder extends Model
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
        'item_id',
        'brand_id',
        'supplier_id',
        'purchase_order_id',
        'item_price_id',
        'created_by'
    ];

    public function arrivalItem()
    {
        return $this->hasMany(ArrivalItem::class, 'po_order_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function uomConversion()
    {
        return $this->belongsTo(UomConversion::class, 'uom_conversion_unit_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function baseUom()
    {
        return $this->belongsTo(Uom::class, 'base_uom_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function itemPrice()
    {
        return $this->belongsTo(ItemPrice::class, 'item_price_id');
    }
}
