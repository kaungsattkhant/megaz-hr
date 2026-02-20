<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractStaff extends Model
{
    //
    protected $fillable=[
        'contract_id',
        'staff_id', 
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
