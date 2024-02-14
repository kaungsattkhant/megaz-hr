<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','is_active'
    ];

    public function items()
    {
        return $this->belongsToMany(Uom::class,'items_uoms', 'item_id', 'uom_id');
    }
}
