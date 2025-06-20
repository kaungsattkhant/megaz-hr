<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NrcTownship extends Model
{
    use HasFactory;
    protected $table = 'nrc_townships';
    protected $fillable = [
        'name_en',
        'name_mm',
        'nrc_code',
    ];
}
