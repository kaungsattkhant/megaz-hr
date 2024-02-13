<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Area;
use App\Models\Department;

class Inventory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'area_id', 'department_id', 'name', 'inventoryable_type', 'inventoryable_id', 'is_active'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function departement()
    {
        return $this->belongsTo(Department::class);
    }
}
