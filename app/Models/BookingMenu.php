<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingMenu extends Model
{
    use HasFactory;

    protected $fillable =[
        'quantity','menu_id','booking_id'
    ];
}
