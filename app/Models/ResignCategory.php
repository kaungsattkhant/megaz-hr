<?php

namespace App\Models;

use App\Models\Resignation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResignCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function resignations()
    {
        return $this->hasMany(Resignation::class);
    }
}
