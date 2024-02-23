<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id','invoice_date','complete_date','created_by','total','tax','sub_total','paid_amount','total_session_price','change','area_id','entity_id','service_id','head_count','payment_status','payment_type','discount_value','customer_id'
    ];

}
