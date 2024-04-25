<?php

namespace App\Models;

use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoGrn extends Model
{
    use HasFactory;
    
    protected $fillable=['inovice_no','invoice_amount','supplier_id','item_id','purchase_order_item_id','remark','quantity'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function purchase_order_item()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

   
}
