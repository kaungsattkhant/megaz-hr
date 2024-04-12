<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItemLeft extends Model
{
    use HasFactory;
    protected $fillable=['quantity','purchase_order_item_id','quantity_by_manager','quantity_by_financial','quantity_by_md','quantity_after_md'];
}
