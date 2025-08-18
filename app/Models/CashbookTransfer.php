<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbookTransfer extends Model
{
    use HasFactory;
    protected $fillable=['id','date_time','amount','cash_account_id','to_cash_account_id','cashbook_balance_id','created_by'];

}
