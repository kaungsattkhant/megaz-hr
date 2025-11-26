<?php

namespace App\Models;

use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'answer',
        'mark',
        'is_active',
        'exam_question_id',
    ];
    protected $casts = [
        'mark' => 'integer',
        'is_active' => 'boolean',
        'exam_question_id' => 'integer',
    ];
    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class);
    }
}
