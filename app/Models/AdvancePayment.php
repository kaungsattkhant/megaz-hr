<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    //
    protected $fillable = ['advance_id','paid_amount', 'payment_month', 'remaining_balance'];
    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }
}
