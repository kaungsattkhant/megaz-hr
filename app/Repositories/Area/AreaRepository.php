<?php

namespace App\Repositories\Area;

use Illuminate\Http\Request;

use App\Models\Area;

class AreaRepository implements AreaRepositoryInterface
{
    public function getAreas(Request $request)
    {
        $areas = Area::with('areaType')->where('is_active', 1)->get();
        if($request->all_minified){
            return $areas;
        }
        $areasData = Pagination($areas, $request, 'areas');

        return $areasData;
    }

    public function createData(array $data)
    {
        $area = Area::create($data);

        return $area;
    }

    public function updateData(array $data, int $id)
    {
        $area = Area::find($id);
        if($area){
            $area->update($data);
        }

        return $area;
    }

    public function deleteData(int $id)
    {
        $area = Area::find($id);
        if($area){
            $area->is_active = 0;
            $area->save();

            return true;
        }

        return false;
    }
}
