<?php

namespace App\Models;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Canteen;
use App\Models\UomConversion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CanteenItem extends Model
{
    use HasFactory;
    protected $fillable=['canteen_id','item_id','uom_id','uom_conversion_id','quantity','amount'];

    public function canteen(){
        return $this->belongsTo(Canteen::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function uom(){
        return $this->belongsTo(Uom::class);
    }

    public function uomConversion(){
        return $this->belongsTo(UomConversion::class,'uom_conversion_id');
    }
}
