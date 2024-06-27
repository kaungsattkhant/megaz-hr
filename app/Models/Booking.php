<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable =[
        'booking_id','date_time','entity_id','head_count_id','package_id','deposit','amount','customer_id','remark','status','confirmed_at','confirmed_by','session','start_date','end_date','cancelled_at','cancelled_by'
    ];
}
