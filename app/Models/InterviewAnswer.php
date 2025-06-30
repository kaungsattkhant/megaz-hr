<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Interview;
use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterviewAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'interview_id',
        'exam_question_id',
        'answer_id',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class);
    }
    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}
