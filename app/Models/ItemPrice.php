<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemPrice extends Model
{
    use HasFactory;

    protected $fillable=['price','item_id','uom_id'];

    public function item(){
        return $this->belongsTo(Item::class);
    }
}
