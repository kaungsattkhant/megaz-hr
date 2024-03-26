<?php

namespace App\Models;

use App\Models\ItemPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name','category_id','is_active'
    ];

    protected $with=['item_prices'];

    public function uoms()
    {
        return $this->belongsToMany(Uom::class,'items_uoms', 'item_id', 'uom_id');
    }

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function item_prices(){
        return $this->hasOne(ItemPrice::class)->latest('created_at');
    }
}
