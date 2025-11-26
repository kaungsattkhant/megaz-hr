<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Division;

class Township extends Model
{
    use HasFactory;

    public function division(){
        return $this->belongsTo(Division::class);
    }

    public function deliveryCharges()
    {
        return $this->hasMany(DeliveryCharge::class);
    }

    public function latestDeliveryCharge()
    {
        return $this->hasOne(DeliveryCharge::class)->latestOfMany();
    }
}
