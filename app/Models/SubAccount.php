<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;
    protected $fillable=['name','account_code','head_account_id','is_active'];

    public function head_account(){
        return $this->belongsTo(\App\Models\HeadAccount::class);
    }
}
