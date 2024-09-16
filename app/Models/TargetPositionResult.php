<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetPositionResult extends Model
{
    use HasFactory;

    protected $fillable=[
        'date_time','invoice_id','role_id','amount'
    ];
}
