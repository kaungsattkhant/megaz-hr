<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'type',
        'category',
        'account_id',
        'main_account_id',
        'cash_account_id',
        'amount',
        'interest_rate',
        'created_by',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function mainAccount()
    {
        return $this->belongsTo(Account::class, 'main_account_id');
    }

    public function cashAccount()
    {
        return $this->belongsTo(Account::class, 'cash_account_id');
    }
}
