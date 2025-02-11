<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'from_date',
        'to_date',
        'place',
        'trained_by',
        'description',
        'created_by',
        'training_type'
    ];

    public function trainedBy()
    {
        return $this->belongsTo(Staff::class, 'trained_by');
    }


    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }


    public function participants(): MorphMany
    {
        return $this->morphMany(Participant::class, 'participantable');
    }
}
