<?php

namespace App\Models;

use App\Models\Sop;
use App\Models\JobDescription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JdSop extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'job_description_id',
    ];

    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class);
    }

    public function sops()
    {
        return $this->hasMany(Sop::class);
    }
}
