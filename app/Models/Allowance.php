<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'name',
        'type',
        'amount',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
