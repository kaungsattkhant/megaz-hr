<?php

namespace App\Repositories\Area;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaRepository implements AreaRepositoryInterface
{
    public function getAreas(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Area::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $areas = Area::where('is_active', 1)->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'areas');
            $paginationData['areas'] = $areas;

            return $paginationData;
        } else {
            if ($request->department_id) {
                $areas = Area::with('areaType')->where('department_id', $request->department_id)->where('is_active', 1)->get();
            } else {
                $areas = Area::with('areaType')->where('is_active', 1)->get();
            }

            return $areas;
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $area = Area::create($data);
            DB::commit();
            return $area;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getAreaByAreaCategory(int $id)
    {
        $areas = Area::where('is_active', 1)->where('area_category_id', $id)->get();
        return $areas;
    }
    public function getAreaByAreaType(int $id)
    {
        $areas = Area::where('is_active', 1)->where('area_type_id', $id)->get();
        return $areas;
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $area = Area::find($id);
            if ($area) {
                $area->update($data);
            }
            DB::commit();
            return $area;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        $area = Area::find($id);
        if ($area) {
            $area->is_active = 0;
            $area->save();

            return true;
        }
        return false;
    }

    public function getAreaByDepartment($department_id)
    {
        return Area::where('department_id',$department_id)->get();      
    }

}
