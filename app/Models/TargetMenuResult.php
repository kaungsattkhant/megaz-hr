<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetMenuResult extends Model
{
    use HasFactory;

    protected $fillable=[
        'date_time','invoice_id','area_id','menu_id','quantity'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
