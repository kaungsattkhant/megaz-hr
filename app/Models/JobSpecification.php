<?php

namespace App\Models;

use App\Models\Skill;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobSpecification extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_specification',
        'job_description_id',
        'created_by',
    ];

    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class);
    }
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_specification_skill','job_specification_id','skill_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }
}
