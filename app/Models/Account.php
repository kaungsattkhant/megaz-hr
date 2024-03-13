<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable=['name','account_code','sub_account_id','is_available'];

    public function sub_account(){
        return $this->belongsTo(\App\Models\SubAccount::class);
    }
}
