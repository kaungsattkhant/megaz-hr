<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignedContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'staff_id',
        'signed_ducument',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
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
