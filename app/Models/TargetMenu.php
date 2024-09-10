<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetMenu extends Model
{
    use HasFactory;

    protected $fillable =[
        'menu_id','quantity','sale_target_menu_id','area_id'
    ];
}
