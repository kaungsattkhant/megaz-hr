<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'description',
        'created_by',
        'warning_type',
    ];

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function type(): MorphOne
    {
        return $this->morphOne(Type::class, 'typeable');
    }

    public function participants(): MorphMany
    {
        return $this->morphMany(Participant::class, 'participantable');
    }
}
