<?php

namespace App\Models;

use App\Models\PaySlip;
use App\Models\Allowance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaySlipAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'pay_slip_id',
        'allowance_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function paySlip()
    {
        return $this->belongsTo(PaySlip::class);
    }
    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
