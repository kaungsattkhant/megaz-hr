<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Accessory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceAccessory extends Model
{
    use HasFactory;
    protected $fillable=['quantity','accessory_id','invoice_id','is_package','accessory_price'];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function accessory(){
        return $this->belongsTo(Accessory::class);
    }
}
