<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackItem extends Model
{
    use HasFactory;

    protected $fillable =[
        'pack_id','item_id','uom_id','quantity'
    ];

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }
     public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
