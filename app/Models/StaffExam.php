<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffExam extends Model
{
    //
    protected $fillable = [
        'staff_id',
        'exam_id',
        'grade_id',
        'total_mark',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class);   
    }
}
