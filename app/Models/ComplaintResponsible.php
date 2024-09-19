<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintResponsible extends Model
{
    use HasFactory;

    protected $fillable=[
        'complaint_id','staff_id'
    ];

    public function  complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
