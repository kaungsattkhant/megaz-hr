<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'from_date',
        'to_date',
        'place',
        'chaired_by',
        'description',
        'created_by',
        'meeting_type' //dep_type,role_type,staff
    ];

    public function chairedBy()
    {
        return $this->belongsTo(User::class, 'chaired_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): MorphMany
    {
        return $this->morphMany(Participant::class, 'participantable');
    }
}
