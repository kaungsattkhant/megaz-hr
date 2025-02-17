<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OrgNew extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'title',
        'description',
        'created_by',
        'org_news_type',
    ];

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function type(): morphMany
    {
        return $this->morphMany(Type::class, 'typeable');
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
