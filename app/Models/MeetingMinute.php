<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingMinute extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'meeting_minute',
        'is_active',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
    public function instructions()
    {
        return $this->hasMany(Instruction::class);
    }

    public function attendances(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'meeting_minute_attendances', 'meeting_minute_id', 'staff_id')
            ->withTimestamps();
    }
}
