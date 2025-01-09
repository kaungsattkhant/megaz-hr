<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierItem extends Model
{
    use HasFactory;
    protected $table='supplier_items';


    protected $fillable =['supplier_id','item_id','brand_id'];

    public $timestamps = false;

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function item_price(){
        return $this->hasOne(ItemPrice::class)->orderBy('id','desc');
    }


}
