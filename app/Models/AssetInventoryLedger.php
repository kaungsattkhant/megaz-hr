<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetInventoryLedger extends Model
{
    use HasFactory;

    protected $fillable=['inventory_id','date_time','quantity','action','asset_id','asset_item_id'];
}
