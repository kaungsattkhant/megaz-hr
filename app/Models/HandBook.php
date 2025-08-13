<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'detail',
        'image_url',
        'image_path',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
