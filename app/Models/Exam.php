<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Grade;
use App\Models\ExamSkill;
use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'role_id',
        'type',
    ];
    protected $casts = [
        'role_id' => 'integer',
        'type' => 'string',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }
    public function examSkills()
    {
        return $this->hasMany(ExamSkill::class);
    }
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
