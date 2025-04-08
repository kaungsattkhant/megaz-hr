<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OffDayAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'off_day_id',
        'offdayable_id',
        'offdayable_type'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function offDay()
    {
        return $this->belongsTo(OffDay::class);
    }

    public function offdayable(): MorphTo
    {
        return $this->morphTo();
    }
}
