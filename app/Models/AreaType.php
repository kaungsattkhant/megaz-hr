<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Area;

class AreaType extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name',
        'type',
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
