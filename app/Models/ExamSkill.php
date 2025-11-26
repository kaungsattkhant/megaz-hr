<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamSkill extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'exam_id',
        'skill_id',
    ];
    protected $casts = [
        'exam_id' => 'integer',
        'skill_id' => 'integer',
    ];
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
