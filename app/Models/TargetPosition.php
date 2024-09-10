<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetPosition extends Model
{
    use HasFactory;

    protected $fillable=[
        'role_id',
        'amount',
        'sale_target_position_id'
    ];

    public function salteTargetPosition()
    {
        return $this->belongsTo(SaleTargetPosition::class);
}
}
