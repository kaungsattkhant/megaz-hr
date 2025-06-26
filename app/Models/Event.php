<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'event_id');
    }
}
