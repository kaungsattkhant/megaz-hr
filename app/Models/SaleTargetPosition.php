<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTargetPosition extends Model
{
    use HasFactory;

    protected $fillable=[
        'month','department_id','head_count',
    ];

    public function getTargetPositionsAmountSumAttribute()
    {
        return $this->targetPositions->sum('amount');
    }

    public function targetPositions()
    {
        return $this->hasMany(TargetPosition::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
