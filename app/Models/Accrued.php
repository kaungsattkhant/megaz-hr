<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accrued extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_time',
        'category',
        'type',
        'account_id',
        'main_account_id',
        'cash_account_id',
        'amount',
        'created_by',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }
}
