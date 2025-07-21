<?php

namespace App\Models;

use App\Models\Role;
use App\Models\JdSop;
use App\Models\JobDescription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sop extends Model
{
    use HasFactory;
    protected $fillable = [
        'sop',
        'role_id',
        'jd_sop_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jdSop()
    {
      return $this->belongsTo(JdSop::class);
    }
}
