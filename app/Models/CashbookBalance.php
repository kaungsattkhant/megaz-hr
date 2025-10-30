<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashbookBalance extends Model
{
    use HasFactory;
    protected $fillable = ['year', 'month', 'opening_balance', 'closing_balance', 'cash_account_id'];
    protected $casts = [
        'closing_balance' => 'float', // 2 decimal places
    ];
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-M-Y') : null;
    }
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'cash_account_id');
    }

}
