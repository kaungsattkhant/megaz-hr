<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoGrn extends Model
{
    use HasFactory;
    
    protected $fillable=['inovice_no','invoice_amount','supplier_id','item_id','purchase_order_id','remark'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

   
}
