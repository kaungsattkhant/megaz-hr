<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingExtraCategory extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    public function extras()
    {
        return $this->hasMany(SellingExtra::class);
    }
}
