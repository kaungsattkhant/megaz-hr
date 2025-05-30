<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'question',
        'type',
        'mark'
    ];

    protected $casts = [
        'interview_id' => 'integer',
        'mark' => 'integer',
    ];
    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
