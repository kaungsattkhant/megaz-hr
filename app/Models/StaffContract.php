<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffContract extends Model
{
    //
    protected $fillable = [
        'staff_id',
        'contract_file_url',
        'contract_file_path',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
