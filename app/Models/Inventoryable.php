<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(\App\Models\inventory::class);
    }

}
