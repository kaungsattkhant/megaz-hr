<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable =[
        'booking_id','date_time','total_session_price','food_total','entity_id','head_count_id','package_id','deposit','amount','customer_id','remark','status','confirmed_at','confirmed_by','session','session_type','start_date','end_date','cancelled_at','cancelled_by'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function headCount()
    {
        return $this->belongsTo(HeadCount::class);
    }

    public function bookingMenus()
    {
        return $this->hasMany(BookingMenu::class);
    }
}
