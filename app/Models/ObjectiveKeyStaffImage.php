<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveKeyStaffImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'objectivekey_staff_id',
        'image_url',
        'image_path'
    ];
    public function objective_key(): BelongsTo
    {
        return  $this->belongsTo(ObjectivekeyStaff::class, 'objectivekey_staff_id');
    }
}
