<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'phone_number',
        'type',
        'is_active'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
