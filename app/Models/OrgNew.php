<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgNew extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'trained_by',
        'description',
        'created_by',
        'org_news_type',
    ];
}
