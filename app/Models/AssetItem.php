<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetItem extends Model
{
    use HasFactory;
    protected $fillable=['name','second_account_id','second_depreciation_account_id','created_by'];
}
