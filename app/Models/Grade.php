<?php

namespace App\Models;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'mark',
        'grade',
        'is_pass',
        'is_active',
        'exam_id',
    ];
    protected $casts = [
        'mark' => 'integer',
        'is_pass' => 'boolean',
        'is_active' => 'boolean',
    ];
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
