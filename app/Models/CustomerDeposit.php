<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'account_id',
        'cash_account_id',
        'amount',
        'type',
        'is_cashier_confirmed',
        'customer_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
