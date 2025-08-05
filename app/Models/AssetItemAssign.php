<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\AssetItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetItemAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'asset_item_id',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function assetItem()
    {
        return $this->belongsTo(AssetItem::class);
    }
}
