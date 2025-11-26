<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditorBalance extends Model
{
    use HasFactory;
    protected $fillable=['amount','date_time','supplier_id','account_id','cash_account_id','type','created_by'];

    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function cash_account(){
        return $this->belongsTo(Account::class);
    }

    public function createdBy(){
        return $this->belongsTo(Staff::class);
    }
}
