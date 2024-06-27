<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'customer_id',
        'address',
        'is_default',
        'township_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function township()
    {
        return $this->belongsTo(Township::class);
    }
}
