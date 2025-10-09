<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pack extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'menu_id','date','expired_at','created_by','status','pack_quantity',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function pack_items()
    {
        return $this->hasMany(PackItem::class);
    }
}
