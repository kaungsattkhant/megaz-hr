<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffBalance extends Model
{
    use HasFactory;
    protected $fillable =[
        'staff_id','year','month','opening_balance','closing_balance'
    ];
}
