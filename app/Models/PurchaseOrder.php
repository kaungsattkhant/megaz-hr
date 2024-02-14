<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable =[
        'po_id','total_price','created_by','kitchen_check_id','financial_check_id','kitchen_check_time','financial_check_time'
    ];
}
