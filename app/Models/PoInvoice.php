<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'date_time',
        'total_invoice_amount',
        'created_by'
    ];

    public function arrivalItems()
    {
        return $this->hasMany(ArrivalItem::class, 'po_invoice_id');
    }
}
