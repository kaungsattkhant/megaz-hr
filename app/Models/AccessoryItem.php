<?php

namespace App\Models;

use App\Models\Uom;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessoryItem extends Model
{
    use HasFactory;
    protected $fillable=['accessory_id','item_id','uom_id','price','quantity'];
    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function uom(){
        return $this->belongsTo(Uom::class);
    }
}
