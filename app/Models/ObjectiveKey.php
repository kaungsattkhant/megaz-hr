<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectiveKey extends Model
{
    protected $fillable = [
        'objective_id',
        'name',
        'okr_point'
    ];
}
