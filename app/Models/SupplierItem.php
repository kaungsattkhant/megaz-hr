<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierItem extends Model
{
    use HasFactory;
    protected $table='supplier_items';


    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }
}
