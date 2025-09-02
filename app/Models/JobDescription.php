<?php

namespace App\Models;

use App\Models\Role;
use App\Models\JobSpecification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobDescription extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'job_description',
        'role_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
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
