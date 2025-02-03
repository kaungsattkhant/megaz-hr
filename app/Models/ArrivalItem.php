<?php

namespace App\Models;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArrivalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_uom_id',
        'base_uom_quantity',
        'uom_id',
        'uom_quantity',
        'uom_conversion_unit_id',
        'quantity',
        'amount',
        'unit_price',
        'po_invoice_id',
        'item_id',
        'supplier_id',
        'purchase_order_id',
        'created_by'
    ];

    public function poInvoice()
    {
        return $this->belongsTo(PoInvoice::class, 'po_invoice_id');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function baseUom()
    {
        return $this->belongsTo(Uom::class, 'base_uom_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function poOrder()
    {
        return $this->belongsTo(PoOrder::class, 'purchase_order_id');
    }
}
