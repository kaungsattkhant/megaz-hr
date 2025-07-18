<?php

namespace App\Models;

use App\Models\ObjectiveStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveStaffImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'objective_staff_id',
        'image_url',
        'image_path'
    ];
    public function objectiveStaff(): BelongsTo
    {
        return  $this->belongsTo(ObjectiveStaff::class, 'objective_staff_id');
    }
}
