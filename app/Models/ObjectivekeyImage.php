<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectivekeyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'objective_key_id',
        'image_url',
        'image_path'
    ];
    public function objective_key(): BelongsTo
    {
        return  $this->belongsTo(ObjectiveKey::class, 'objective_key_id');
    }
}
