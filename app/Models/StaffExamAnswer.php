<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffExamAnswer extends Model
{
    //
    protected $fillable = [
        'staff_exam_id',
        'exam_question_id',
        'answer_id',
    ];      
    public function staff_exam()
    {
        return $this->belongsTo(StaffExam::class);
    }   
    public function exam_question()
    {
        return $this->belongsTo(ExamQuestion::class);
    }   
    public function answer()
    {
        return $this->belongsTo(Answer::class);     
    }
}
