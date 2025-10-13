<?php

namespace App\Repositories\Area;

use App\Models\Area;
use App\Models\MenuArea;
use App\Models\MenuPlace;
use App\Models\AreaCategory;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\MenuCategoryArea;
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
            $area = Area::updateOrCreate(['id' => $data['id']], $data);
            $menuCategories = $area->menuCategories;
            if (!isset($data['menu_category_ids'])) {
                ResponseMessage('Menu Category Ids is required', 419);
            }
            if (isset($data['menu_category_ids'])) {
                //work code
                foreach ($menuCategories as $menuC) {
                    MenuArea::where('menu_category_area_id', $menuC->pivot->id)->delete();
                }
                $menuCategoryIds = json_decode($data['menu_category_ids']);
                $area->menuCategories()->sync($menuCategoryIds);
                foreach ($menuCategoryIds as $menuCategoryId) {
                    $menuPlaces = MenuPlace::with('cooking_place.area')
                        ->whereHas('menu', fn($q) => $q->where('menu_category_id', $menuCategoryId))
                        ->get();
                    $total = count($menuPlaces);
                    $counter = 1;
                    foreach ($menuPlaces as $index => $menuPlace) {
                        $menuCategoryAreas = MenuCategoryArea::where('menu_category_id', $menuCategoryId)
                            ->where('selling_area_id', $area->id)
                            ->get();

                        foreach ($menuCategoryAreas as $menuCategoryArea) {
                            $menuArea = MenuArea::updateOrCreate(
                                [
                                    'menu_category_area_id' => $menuCategoryArea->id,
                                    'cooking_area_id' => $menuPlace->cooking_place->area_id,
                                ],
                                [
                                    'is_default' => $counter === $total ? 1 : 0,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]
                            );
                            $counter++;
                        }
                    }
                }
                //end

                //performance code but descn't stable
                // foreach($menuCategories as $menuC){
                //     MenuArea::where('menu_category_area_id',$menuC->pivot->id)->delete();
                // }
                // $menuCategoryIds = is_string($data['menu_category_ids'])
                //     ? json_decode($data['menu_category_ids'], true)
                //     : $data['menu_category_ids'];
                // $area->menuCategories()->sync($menuCategoryIds);
                // $menuPlaces = MenuPlace::with('cooking_place.area')
                //     ->whereHas('menu', fn($q) => $q->whereIn('menu_category_id', $menuCategoryIds))
                //     ->get();
                // $cookingAreaIds = $menuPlaces
                //     ->pluck('cooking_place.area.id')
                //     ->filter()
                //     ->unique()
                //     ->values();
                // $menuCategoryAreas = MenuCategoryArea::whereIn('menu_category_id', $menuCategoryIds)
                // ->where('selling_area_id', $area->id)
                //     ->get();
                // $now = now();
                // $insertData = [];
                // foreach ($menuCategoryAreas as $menuCategoryArea) {
                //     foreach ($cookingAreaIds as $index => $cookingAreaId) {
                //         $existing = MenuArea::where('menu_category_area_id', $menuCategoryArea->id)
                //             ->where('cooking_area_id', $cookingAreaId)
                //             ->first();
                //         if (!$existing) {
                //             $insertData[] = [
                //                 'menu_category_area_id' => $menuCategoryArea->id,
                //                 'cooking_area_id' => $cookingAreaId,
                //                 'is_default' => $index === 0 ? 1 : 0, // 🔥 only the first cooking area per category gets default
                //                 'created_at' => $now,
                //                 'updated_at' => $now,
                //             ];
                //         }

                //     }
                // }
                // if (!empty($insertData)) {
                //     MenuArea::upsert(
                //         $insertData,
                //         ['menu_category_area_id', 'cooking_area_id'], // unique keys
                //         ['is_default', 'updated_at']
                //     );
                // }
                //end

            }
            // if (!isset($data['id'])) {
            //     $sellingAreaCategory = AreaCategory::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [strtolower(str_replace(' ', '', 'Selling Area'))])
            //         ->first();
            //     if (($area->areaCategory->id === $sellingAreaCategory->id) && ($area->areaCategory->name === $sellingAreaCategory->name)) {
            //         $menuCategoryIds = MenuCategory::all()->pluck('id')->toArray();
            //         $area->menuCategories()->sync($menuCategoryIds);
            //     }
            // }
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
        return Area::orderBy('id', 'desc')->get();
    }

    public function getSellingAreas($request)
    {
        $areas = Area::with([
            'areaCategory',
            'areaType',
            'inventoryable.inventory'
        ])->where('is_active', 1)
            ->whereHas('areaCategory', function ($query) {
                $query->where('name', 'Selling Area');
            })
            ->get();
        if ($areas->isEmpty()) {
            return ResponseData([], 404, false, 'Areas not found.');
        }

        return $areas;
    }

    public function getCookingAreas($request)
    {
        $areas = Area::with(['areaCategory', 'areaType', 'inventoryable.inventory'])->where('is_active', 1)
            ->whereHas('areaCategory', function ($query) {
                $query->where('name', 'Cooking Area');
            })
            ->get();
        return $areas;
    }
}
