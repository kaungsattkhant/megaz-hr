<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id','date','expired_at','created_by','status','pack_quantity',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function packItems()
    {
        return $this->hasMany(PackItem::class);
    }
}
