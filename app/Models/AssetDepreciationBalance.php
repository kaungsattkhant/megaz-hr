<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDepreciationBalance extends Model
{
    use HasFactory;
    protected $fillable = ['month', 'year', 'date', 'asset_item_id', 'asset_id', 'original_cost', 'addition_year_cost', 'total_cost', 'current_month_depreciation', 'addition_year_depreciation', 'total_depreciation', 'third_account_id', 'third_depreciation_account_id', 'book_value'];
}
