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
        'quantity','purchase_order_id','item_id','amount',
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

}
