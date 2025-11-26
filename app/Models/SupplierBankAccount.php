<?php

namespace App\Models;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'account_name',
        'account_number',
        'is_active'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
