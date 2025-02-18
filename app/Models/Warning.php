<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'title',
        'description',
        'created_by',
        'warning_type',
        'type_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function participants(): MorphMany
    {
        return $this->morphMany(Participant::class, 'participantable');
    }

    public function notification()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
