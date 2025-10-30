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
        $areasQuery = Area::with(['areaType', 'areaCategory','menuCategories'])->orderBy('created_at', 'desc');

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
                // foreach ($menuCategories as $menuC) {
                //     MenuArea::where('menu_category_area_id', $menuC->pivot->id)->delete();
                // }
                // $menuCategoryIds = json_decode($data['menu_category_ids']);
                // $area->menuCategories()->sync($menuCategoryIds);
                // foreach ($menuCategoryIds as $menuCategoryId) {
                //     $menuPlaces = MenuPlace::with('cooking_place.area')
                //         ->whereHas('menu', fn($q) => $q->where('menu_category_id', $menuCategoryId))
                //         ->get();
                //     $total = count($menuPlaces);
                //     $counter = 1;
                //     foreach ($menuPlaces as $index => $menuPlace) {
                //         $menuCategoryAreas = MenuCategoryArea::where('menu_category_id', $menuCategoryId)
                //             ->where('selling_area_id', $area->id)
                //             ->get();

                //         foreach ($menuCategoryAreas as $menuCategoryArea) {
                //             $menuArea = MenuArea::updateOrCreate(
                //                 [
                //                     'menu_category_area_id' => $menuCategoryArea->id,
                //                     'cooking_area_id' => $menuPlace->cooking_place->area_id,
                //                 ],
                //                 [
                //                     'is_default' => $counter === $total ? 1 : 0,
                //                     'created_at' => now(),
                //                     'updated_at' => now(),
                //                 ]
                //             );
                //             $counter++;
                //         }
                //     }
                // }
                //end

                //performance code but descn't stable
                $menuCategoryIds = json_decode($data['menu_category_ids'], true);

                // Remove old menu areas linked to this area
                $menuCategoryPivotIds = $area->menuCategories()->pluck('menu_category_areas.id')->toArray() ?? [];
                MenuArea::whereIn('menu_category_area_id', $menuCategoryPivotIds)->delete();

                // Sync menu categories for the area
                $area->menuCategories()->sync($menuCategoryIds);

                $insertData = [];
                $now = now();

                foreach ($menuCategoryIds as $menuCategoryId) {
                    // Get menu places and unique cooking_area_ids
                    $menuPlaces = MenuPlace::with('cooking_place.area')
                        ->whereHas('menu', fn($q) => $q->where('menu_category_id', $menuCategoryId))
                        ->get();

                    // Extract unique cooking area IDs only
                    $cookingAreaIds = $menuPlaces
                        ->pluck('cooking_place.area_id')
                        ->unique()
                        ->filter()
                        ->values();

                    if ($cookingAreaIds->isEmpty()) {
                        continue;
                    }

                    // Fetch menu category areas for this category + selling area
                    $menuCategoryAreas = MenuCategoryArea::where('menu_category_id', $menuCategoryId)
                        ->where('selling_area_id', $area->id)
                        ->get();

                    if ($menuCategoryAreas->isEmpty()) {
                        continue;
                    }

                    // Determine which one should be default — the *last* cooking area
                    $defaultCookingAreaId = $cookingAreaIds->last();

                    foreach ($menuCategoryAreas as $menuCategoryArea) {
                        foreach ($cookingAreaIds as $cookingAreaId) {
                            $insertData[] = [
                                'menu_category_area_id' => $menuCategoryArea->id,
                                'cooking_area_id' => $cookingAreaId,
                                'is_default' => $cookingAreaId == $defaultCookingAreaId ? 1 : 0,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }
                }

                // Bulk upsert for performance
                if (!empty($insertData)) {
                    MenuArea::upsert(
                        $insertData,
                        ['menu_category_area_id', 'cooking_area_id'], // unique constraint
                        ['is_default', 'updated_at']
                    );
                }

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
