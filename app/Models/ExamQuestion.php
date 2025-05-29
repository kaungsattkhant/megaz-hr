<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamQuestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'exam_id',
        'question',
        'type',
        'is_active',
    ];
    protected $casts = [
        'exam_id' => 'integer',
        'is_active' => 'boolean',
    ];
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
