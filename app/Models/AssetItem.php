<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetItem extends Model
{
    use HasFactory;
    protected $fillable=['name','item_code','second_account_id','second_depreciation_account_id','created_by'];

    public function second_account(){
        return $this->belongsTo(Account::class,'second_account_id');
    }

    public function second_depreciation_account(){
        return $this->belongsTo(Account::class,'second_depreciation_account_id');
    }

}
