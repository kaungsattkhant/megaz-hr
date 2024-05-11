<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackMenu extends Model
{
    use HasFactory;

    protected $fillable =[
        'pack_id','item_id','uom_id','quantity','menu_id'
    ];
}
