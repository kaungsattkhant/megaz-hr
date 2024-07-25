<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecondAccount extends Model
{
    use HasFactory;
    protected $fillable=['name','account_code','account_id','is_active'];

    public function account(){
        return $this->belongsTo(Account::class);
    }
}
