<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemPrice extends Model
{
    use HasFactory;

    protected $fillable=['price','item_id','uom_id','supplier_item_id','base_uom_id'];

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function supplier_item(){
        return $this->belongsTo(SupplierItem::class);
    }

    public function uom(){
        return $this->belongsTo(Uom::class);
    }

}
