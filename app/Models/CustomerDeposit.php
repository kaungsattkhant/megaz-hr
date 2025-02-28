<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
