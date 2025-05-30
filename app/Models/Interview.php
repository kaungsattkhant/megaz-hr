<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Staff;
use App\Models\CustomQuestion;
use App\Models\InterviewAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interview extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'exam_id',
        'total_mark',
        'created_by',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function interviewAnswers()
    {
        return $this->hasMany(InterviewAnswer::class);
    }
    public function customQuestions()
    {
        return $this->hasMany(CustomQuestion::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }
}
