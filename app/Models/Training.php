<?php

namespace App\Models;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'from_date',
        'to_date',
        'title',
        'place',
        'trained_by',
        'description',
        'created_by',
        'training_type',
        'type_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function trainedBy()
    {
        return $this->belongsTo(Staff::class, 'trained_by');
    }

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
