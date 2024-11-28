<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    
    protected $fillable=['name','shop_name','phone_number','address','credit_limit','account_id','creditor_account_id'];

    public function items()
    {
        return $this->belongsToMany(Item::class,'supplier_items');
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function creditorAccount(){
        return $this->belongsTo(Account::class,'creditor_account_id');
    }
}
