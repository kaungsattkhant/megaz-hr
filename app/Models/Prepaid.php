<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prepaid extends Model
{
    use HasFactory;

    protected $fillable=[
        'title','account_id','from_date','to_date','total_amount','prepaid_amount','monthly_cost','created_by'
    ];

    public function prepaidPayments()
    {
        return $this->hasMany(PrepaidPayment::class);
    }

    public function prepaidBalance()
    {
        return $this->hasOne(PrepaidBalance::class)->latest();
    }

}
