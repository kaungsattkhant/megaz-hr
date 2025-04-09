<?php

namespace App\Models;

use App\Models\DayInOffDay;
use App\Models\OffDayAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OffDay extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'repetition',
        'created_by',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // public function createdBy()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    public function days(): HasMany
    {
        return $this->hasMany(DayInOffDay::class);
    }

    public function OffDayAssignments(): HasMany
    {
        return $this->hasMany(OffDayAssignment::class);
    }
}
