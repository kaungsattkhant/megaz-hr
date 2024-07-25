<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    use HasFactory;

    protected $fillable=[
        'township_id','amount','date_time','is_active'
    ];

    public function township()
    {
        return $this->belongsTo(Township::class);
    }
}
