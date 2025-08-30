<?php

namespace App\Models;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventoryable extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_id', 'inventoryable_type', 'inventoryable_id'];

    protected $with=['inventoryable'];

    public function inventoryable()
    {
        return $this->morphTo();
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
