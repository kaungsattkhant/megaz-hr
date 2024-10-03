<?php

namespace App\Models;

use App\Models\ItemPrice;
use App\Models\UomConversion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name','category_id','is_active','base_uom_id','code'
    ];

    protected $with=['item_prices'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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

    public function suppliers(){
        return $this->belongsToMany(Supplier::class, 'supplier_items');

    }


    public function uomConversion()
    {
        return $this->hasOneThrough(
            UomConversion::class,
            ItemPrice::class,
            'item_id', // Foreign key on ItemPrice table
            'conversion_unit_id', // Foreign key on UomConversion table
            'id', // Local key on Item table
            'uom_id' // Local key on ItemPrice table
        );
    }

    public function getItemPriceWithConversionAttribute()



    {
        // Calculate the item price with conversion
        $itemPrice = $this->item_prices->price; // Price of the item
        $conversionRate = $this->uomConversion->conversion; // Conversion rate
        return $itemPrice * $conversionRate;
    }
}
