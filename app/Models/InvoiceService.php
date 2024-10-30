<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceService extends Model
{
    use HasFactory;
    protected $with=['service'];
    protected $fillable=['start_date','end_date','invoice_id','service_id','service_value'];
    
    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }
}
