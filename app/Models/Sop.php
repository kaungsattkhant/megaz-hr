<?php

namespace App\Models;

use App\Models\Role;
use App\Models\JobDescription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sop extends Model
{
    use HasFactory;
    protected $fillable = [
        'sop',
        'role_id',
        'job_description_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class);
    }
}
