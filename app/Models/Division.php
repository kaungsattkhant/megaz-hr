<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Township;

class Division extends Model
{
    use HasFactory;

    public function townships(){
        return $this->hasMany(Township::class);
    }
}
