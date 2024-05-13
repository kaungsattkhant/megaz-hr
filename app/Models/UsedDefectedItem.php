<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedDefectedItem extends Model
{
    use HasFactory;

    protected $fillable =[
        'date','item_id','uom_id','type','quantity','remark','created_by'
    ];
}
