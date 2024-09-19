<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintCarbonCopy extends Model
{
    use HasFactory;

    protected $fillable=[
        'staff_id','complaint_id'
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function complaint(){
        return $this->belongsTo(Complaint::class);
    }
}
