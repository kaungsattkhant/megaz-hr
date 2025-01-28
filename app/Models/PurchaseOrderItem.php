<?php

namespace App\Models;

use App\Models\Item;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends BaseModel
{
    use HasApiTokens, HasFactory;

    protected $with = ['item', 'uom'];
    protected $fillable = [
        'item_id',
        'brand_id',
        'base_uom_id',
        'base_uom_quantity',
        'uom_id',
        'uom_quantity',
        'uom_conversion_id',
        'quantity',
        'purchase_order_id',
        'amount',
        'unit_price',
        'original_quantity',
        'is_manager_checked',
        'is_financial_checked',
        'is_md_checked',
        'is_procurement_manager_checked',
        'is_confirmed'
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function baseUom()
    {
        return $this->belongsTo(Uom::class, 'base_uom_id');
    }

    public function purchaseOrderItemLefts()
    {
        return $this->hasMany(PurchaseOrderItemLeft::class);
    }

    public function purchaseOrderItemLeft()
    {
        return $this->hasOne(PurchaseOrderItemLeft::class);
    }

    public function poGrn()
    {
        return $this->hasOne(PoGrn::class);
    }

    public function uomConversion()
    {
        return $this->belongsTo(UomConversion::class, 'uom_conversion_id');
    }
}
