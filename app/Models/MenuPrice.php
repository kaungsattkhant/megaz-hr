<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Menu;

class MenuPrice extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'price'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
