<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackItem extends Model
{
    use HasFactory;

    protected $fillable =[
        'pack_id','item_id','uom_id','quantity','menu_id'
    ];

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }
}
