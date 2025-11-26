<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAdvance extends Model
{
    use HasFactory;

    protected $fillable =[
        'date_time','amount','staff_id','type','cash_account_id','created_by'
    ];
}
