<?php

namespace App\Models;

use App\Models\Floor;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'floor_id',
        'staff_id'
    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
