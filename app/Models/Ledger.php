<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $fillable=['value','personable_id','personable_type','action','is_cashier_confirmed','transaction_id','account_id'];
}
