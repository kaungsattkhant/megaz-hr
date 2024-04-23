<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAssetDepreciation extends Model
{
    use HasFactory;
    protected $fillable=[
        'fixed_asset_purchase_id','date','depreciated_amount'
    ];
}
