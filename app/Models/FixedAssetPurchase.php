<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAssetPurchase extends Model
{
    use HasFactory;

    protected $fillable=[
        'fixed_asset_id',
        'date',
        'name',
        'description',
        'total_price',
        'remaining_price',
        'depreciation_amount',
        'total_duration',
        'remaining_duration',
        'start_date',
        'created_by',
        'bought_by',
        'is_bought',
        'manager_check_id',
        'manager_check_time',
        'md_check_time',
        'is_md_checked',
        'status'
    ];


    public function fixedAssetDepreciation()
    {
        return $this->belongsTo(FixedAssetDepreciation::class);
    }

}
