<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected function getCreatedAt()
    {
        return $this->created_at;
    }

    protected function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
