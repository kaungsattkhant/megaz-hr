<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_uom_id',
        'base_uom_quantity',
        'uom_id',
        'uom_quantity',
        'quantity',
        'amount',
        'po_invoice_id',
        'po_order_id',
        'created_by'
    ];
}
