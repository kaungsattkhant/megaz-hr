<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $fillable = [
        'meeting_minute_id',
        'objective_id',
        'okr_point',
        'project_id',
        'tag',
        'assigned_to',
        'start_date',
        'due_date',
        'remark',
        'priority',
        'responsible_id',
        'accountable_id',
        'consulted_id',
        'informed_id',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function assignedTo()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }
    public function responsible()
    {
        return $this->belongsTo(Staff::class, 'responsible_id');
    }
    public function accountable()
    {
        return $this->belongsTo(Staff::class, 'accountable_id');
    }
    public function consulted()
    {
        return $this->belongsTo(Staff::class, 'consulted_id');
    }
    public function informed()
    {
        return $this->belongsTo(Staff::class, 'informed_id');
    }
    public function objectiveKeys(): BelongsToMany
    {
        return $this->belongsToMany(ObjectiveKey::class, 'instruction_objective_keys', 'instruction_id', 'objective_key_id')
            ->withTimestamps();
    }

    public function meetingMinute()
    {
        return $this->belongsTo(MeetingMinute::class);
    }
}
