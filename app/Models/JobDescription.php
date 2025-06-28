<?php

namespace App\Models;

use App\Models\Role;
use App\Models\JobSpecification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobDescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_description',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jobSpecification()
    {
        return $this->hasMany(JobSpecification::class);
    }
}
