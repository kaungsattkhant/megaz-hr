<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=['date','description','transactionable_id','transactionable_type','is_confirmed','created_by','inventory_id'];

    public function ledgers(){
        return $this->hasMany(\App\Models\Ledger::class);
    }
}
