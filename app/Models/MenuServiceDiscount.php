<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuServiceDiscount extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','from_date','to_date','type','discountable_id','discountable_type','discount_price','created_by','is_active'
    ];

    public function discountable()
    {
        return $this->morphTo();
    }
}
