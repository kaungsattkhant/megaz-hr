<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepaidPayment extends Model
{
    use HasFactory;

    protected $fillable =[
        'date_time','amount','cash_account_id','prepaid_id'
    ];
}
