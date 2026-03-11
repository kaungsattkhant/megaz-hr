<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Alignment;
use App\Models\KpiSnapshot;

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

    public function alignments(): BelongsToMany
    {
        return $this->belongsToMany(Alignment::class, 'meeting_minute_alignments', 'meeting_minute_id', 'alignment_id')
            ->withPivot('remark')
            ->withTimestamps();
    }

    public function kpiSnapshots(): BelongsToMany
    {
        return $this->belongsToMany(KpiSnapshot::class, 'meeting_minute_kpi_snapshots', 'meeting_minute_id', 'kpi_snapshot_id')
            ->withPivot('value')
            ->withTimestamps();
    }
}
