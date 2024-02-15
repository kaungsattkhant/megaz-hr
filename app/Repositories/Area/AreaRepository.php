<?php

namespace App\Repositories\Area;

use Illuminate\Http\Request;

use App\Models\Area;

class AreaRepository implements AreaRepositoryInterface
{
    public function getAreas(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Area::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $areas = Area::where('is_active', 1)->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'areas');
            $paginationData['areas'] = $areas;

            return $paginationData;
        }

        else{
            $areas = Area::with('areaType')->where('is_active', 1)->get();

            return $areas;
        }
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
