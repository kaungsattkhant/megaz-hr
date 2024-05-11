<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id','date','expired_at','created_by','expired_at','created_by','status'
    ];

    public function packMenus()
    {
        return $this->hasMany(PackMenu::class);
    }
}
