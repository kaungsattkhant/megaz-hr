<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable=[
        'invoice_date','start_date','end_date','head_count','created_by','total','tax','sub_total','paid_amount','change','area_id','entity_id','service_id','payment_status','payment_type'
    ];
}
