<?php

namespace App\Models;

use App\Models\Item;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends BaseModel
{
    use HasApiTokens,HasFactory;

    protected $with=['item'];
    protected $fillable=[
        'quantity','purchase_order_id','item_id','amount','original_quantity','is_grn','uom_id','uom_conversion_id'
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function purchase_order(){
        return $this->belongsTo(PurchaseOrder::class);
    }
    
    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function uom(){
        return $this->belongsTo(Uom::class);
    }

    public function purchaseOrderItemLefts(){
        return $this->hasMany(PurchaseOrderItemLeft::class);
    }

    public function purchaseOrderItemLeft(){
        return $this->hasOne(PurchaseOrderItemLeft::class);
    }

    public function poGrn(){
        return $this->hasOne(PoGrn::class);
    }

    public function conversionUom(){
        return $this->belongsTo(UomConversion::class,'conversion_id');
    }

}
