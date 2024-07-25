<?php

namespace App\Models;

use App\Models\Account;
use App\Models\SecondAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThirdAccount extends Model
{
    use HasFactory;
    protected $fillable=['name','account_code','second_account_id','is_active'];

    public function second_account(){
        return $this->belongsTo(SecondAccount::class,'second_account_id');
    }
}
