<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractStaff extends Model
{
    //
    protected $fillable=[
        'contract_id',
        'staff_id', 
        'signed_document',
        'signed_at',
        'confirmed_by',
        'confirmed_at',
        'cancelled_by',
        'cancelled_at',
        'status',
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }
    public function cancelledBy()
    {
        return $this->belongsTo(Staff::class, 'cancelled_by');
    }
    
}
