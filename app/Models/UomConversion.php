<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UomConversion extends Model
{
    use HasFactory;

    protected $fillable =[
        'base_unit_id','conversion_unit_id','conversion','is_show', 'is_active','created_by'
    ];

    public function createdStaff()
    {
        return $this->belongsTo(Staff::class,'created_by');
    }

    public function baseUnit()
    {
        return $this->belongsTo(Uom::class, 'base_unit_id');
    }

    public function conversionUnit()
    {
        return $this->belongsTo(Uom::class, 'conversion_unit_id');
    }
}
