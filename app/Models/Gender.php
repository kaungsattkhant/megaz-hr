<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Staff;

class Gender extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
