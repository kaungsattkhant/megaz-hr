<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MeetingMinute;

class KpiSnapshot extends Model
{
    protected $fillable = ['name'];
    public function meetingMinutes(): BelongsToMany
    {
        return $this->belongsToMany(MeetingMinute::class, 'meeting_minute_kpi_snapshots', 'kpi_snapshot_id', 'meeting_minute_id')
            ->withPivot('value')
            ->withTimestamps();
    }
}
