<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable=['name','cost','quantity','purchase_date','third_account_id','third_depreciation_account_id','useful_life','cash_account_id','asset_item_id','created_by'];

    public function third_account(){
        return $this->belongsTo(Account::class,'third_account_id');
    }

    public function third_depreciation_account(){
        return $this->belongsTo(Account::class,'third_depreciation_account_id');
    }
}
