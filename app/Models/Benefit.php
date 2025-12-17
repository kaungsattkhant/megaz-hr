<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    //
    protected $fillable = ['name','type','menu_id','cost','status','created_by'];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
