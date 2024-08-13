<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepaidBalance extends Model
{
    use HasFactory;

    protected $fillable =[
        'year','month','opening_balance','closing_balance','prepaid_amount','monthly_cost','cost','prepaid_id'
    ];
}
