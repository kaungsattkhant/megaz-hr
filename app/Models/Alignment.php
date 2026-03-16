<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MeetingMinute;

class Alignment extends Model
{
    protected $fillable = ['name'];
    public function meetingMinutes(): BelongsToMany
    {
        return $this->belongsToMany(MeetingMinute::class, 'meeting_minute_alignments', 'alignment_id', 'meeting_minute_id')
            ->withPivot('remark')
            ->withTimestamps();
    }
}
