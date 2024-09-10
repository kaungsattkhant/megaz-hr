<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTargetMenu extends Model
{
    use HasFactory;

    protected $fillable=[
        'month',
    ];

    public function targetMenus()
    {
        return $this->hasMany(TargetMenu::class);
    }
}
