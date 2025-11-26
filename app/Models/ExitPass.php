<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\ExitCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExitPass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exit_category_id',
        'staff_id',
        'detail',
        'exit_date_time',
        'arrival_date_time',
        'status',
        'arrival_at'
    ];
    public function exitCategory(): BelongsTo
    {
        return $this->belongsTo(ExitCategory::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
