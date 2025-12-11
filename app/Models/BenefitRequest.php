<?php

namespace App\Models;

use App\Models\Benefit;
use Illuminate\Database\Eloquent\Model;

class BenefitRequest extends Model
{
    //
    protected $fillable = ['date_time','benefit_id','staff_id','detail','status','image','created_by','confirmed_at','confirmed_by','cancelled_at','cancelled_by'];

    public function benefit(){
        return $this->belongsTo(Benefit::class);
    }
}
