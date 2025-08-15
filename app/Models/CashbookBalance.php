<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbookBalance extends Model
{
    use HasFactory;
    protected $fillable=['year','month','opening_balance','closing_balance','cash_account_id'];
    protected $casts = [
        'closing_balance' => 'float', // 2 decimal places
    ];
    public function account(){
        return $this->belongsTo(\App\Models\Account::class,'cash_account_id');
    }
    
}
