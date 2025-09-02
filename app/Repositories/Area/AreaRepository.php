<?php

namespace App\Repositories\Area;

use App\Models\Area;
use App\Models\AreaCategory;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaRepository implements AreaRepositoryInterface
{
    public function getAreas(Request $request)
    {
        $areasQuery = Area::with(['areaType', 'areaCategory'])->orderBy('created_at', 'desc');

        if ($request->department_id) {
            $areas = $areasQuery->where('department_id', $request->department_id)->get();
        } elseif ($request->area_category_id) {
            $areas = $areasQuery
                ->whereHas('areaCategory', function ($query) use ($request) {
                    $query->where('id', $request->area_category_id);
                })
                ->get();
        } elseif (isset($request->page)) {
            $areas = $areasQuery->paginate(config('common.list_count'));
        } else {
            $areas = $areasQuery->where('is_active', 1)->get();
        }

        return $areas;
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            $area = Area::updateOrCreate(['id'=>$data['id']],$data);
            if (!isset($data['id'])) {
                $sellingAreaCategory = AreaCategory::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [strtolower(str_replace(' ', '', 'Selling Area'))])
                    ->first();
                if (($area->areaCategory->id === $sellingAreaCategory->id) && ($area->areaCategory->name === $sellingAreaCategory->name)) {
                    $menuCategoryIds = MenuCategory::all()->pluck('id')->toArray();
                    $area->menuCategories()->sync($menuCategoryIds);
                }
            }
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
        $areas = Area::where('is_active', 1)
            ->where('area_category_id', $id)
            ->get();
        return $areas;
    }
    public function getAreaByAreaType(int $id)
    {

        $areas = Area::where('is_active', 1)->where('area_category_id', $id)->get();
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
        return Area::orderBy('id','desc')->get();
    }

    public function getSellingAreas($request)
    {
        $areas = Area::with(['areaCategory', 'areaType'])->where('is_active', 1)
            ->whereHas('areaCategory', function ($query) {
                $query->where('name', 'Selling Area');
            })
            ->get();
            if($areas->isEmpty()){
                return ResponseData([], 404, false, 'Areas not found.');
            }

        return $areas;
    }

    public function getCookingAreas($request)
    {
        $areas = Area::with(['areaCategory', 'areaType'])->where('is_active', 1)
            ->whereHas('areaCategory', function ($query) {
                $query->where('name', 'Cooking Area');
            })
            ->get();

        return $areas;
    }
}
