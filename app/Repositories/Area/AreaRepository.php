<?php

namespace App\Repositories\Area;

use App\Models\Area;

class AreaRepository implements AreaRepositoryInterface
{
    public function getAreas()
    {
        return Area::with('areaType')->where('is_active', 1)->get();
    }
}
