<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomSession extends Model
{
    use HasFactory;

    protected $fillable=[
        'start_date','end_date','session_duration','invoice_id'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
