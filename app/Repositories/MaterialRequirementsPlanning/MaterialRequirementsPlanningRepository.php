<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Exception;
use App\Models\Area;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Remark;
use App\Models\SubMenu;
use App\Models\MenuArea;
use App\Models\MenuStep;
use App\Models\MenuPrice;
use App\Models\Department;
use App\Models\AreaCategory;
use App\Models\CookingPlace;
use App\Models\MenuCategory;
use App\Models\MenuStepItem;
use Illuminate\Http\Request;
use App\Models\MenuCategoryArea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaterialRequirementsPlanningRepository implements MaterialRequirementsPlanningInterface
{
  public function getMrpLists(Request $request)
  {
    $search = $request->input('search');
    $category = $request->input('menu_category_id'); //change to category_id to menu_category_id
    $price = $request->input('price');
    $code = $request->input('code');
    $menuList = Menu::menuFilter($search, $price, $category, $code)->with([
      'menu_category',
      'price',
      'menuPlaces.area',
      'menuSteps.role.department',
      'menuSteps.menuStepItem.item',
      'menuSteps.menuStepItem.uom',
      'subMenus.menuSteps.menuStepItem.item',
      'subMenus.menuSteps.menuStepItem.uom',
    ])->orderByDesc('id')->paginate(config('common.list_count'));
    return $menuList;
  }
  public function store($validatedData)
  {
    DB::beginTransaction();
    try {
      $data = [];
      if (isset($validatedData['image'])) {
        $imageData = $validatedData['image'];
        $extension = $imageData->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $data['image_path'] = $imageData->storeAs('menuImages/', $hashedName, 'public');
        $data['image_url'] = Storage::url($data['image_path']);
      }

      $menu = Menu::create([
        'name' => $validatedData['name'],
        'menu_category_id' => $validatedData['menu_category_id'],
        'code' => $validatedData['code'],
        'description' => $validatedData['description'],
        'image_path' => $data['image_path'] ?? null,
        'image_url' => $data['image_url'] ?? null,
      ]);
      if (isset($validatedData['menu_steps'])) {
        $menuSteps = json_decode($validatedData['menu_steps']);
        foreach ($menuSteps as $step) {
          if ($step->type === "ready_to_sale") {
            if (!isset($step->expired_at)) {
              return ResponseMessage("The 'expired_at' field is required for type 'ready_to_sale'.", 402);
            }
          }
          $menuStep = MenuStep::create([
            'menu_id' => $menu->id,
            'role_id' => $step->role_id,
            'duration' => $step->duration,
            'order_time' => $step->order_time,
            'expected_quantity' => $step->expected_quantity,
            'level' => $step->level,
            'type' => $step->type,
            'expired_at' => $step->expired_at ?? null,
          ]);

          if (isset($step->item_menu)) {
            foreach ($step->item_menu as $itemData) {

              $quantity = ($itemData->uom_type === 'base_uom')
                ? $itemData->weight * $itemData->uom_conversion
                : $itemData->weight;

              MenuStepItem::create([
                'menu_step_id' => $menuStep->id,
                'item_id' => $itemData->item_id,
                'uom_id' => $itemData->uom_id,
                'quantity' => $quantity,
                'weight' =>  $itemData->weight,
                'uom_type' => $itemData->uom_type
              ]);
            }
          }
        }
      }


      if (isset($validatedData['price'])) {
        MenuPrice::create([
          'menu_id' => $menu->id,
          'price' => $validatedData['price']
        ]);
      }

      if (isset($validatedData['cooking_place_id'])) {
        $cookingPlace = json_decode($validatedData['cooking_place_id']);
        $menu->menuPlaces()->sync($cookingPlace);
        if (isset($validatedData['menu_category_id'])) {
          $menuCategory = MenuCategory::findOrFail($validatedData['menu_category_id']);
          foreach ($cookingPlace  as $place) {
            $place = CookingPlace::findOrFail($place);
            $areaId = $place->area_id;
            $menuCategoryAreas = MenuCategoryArea::where('menu_category_id', $menuCategory->id)
              ->get();
            foreach ($menuCategoryAreas as $menuCategoryArea) {
              MenuArea::where('menu_category_area_id', $menuCategoryArea->id)
                ->update(['is_default' => 0]);
              MenuArea::updateOrCreate(
                [
                  'menu_category_area_id' => $menuCategoryArea->id,
                  'cooking_area_id' =>  $areaId,
                ],
                [
                  'menu_category_area_id' => $menuCategoryArea->id,
                  'cooking_area_id' =>  $areaId,
                  'is_default' => 1,
                ]
              );
            }
          }
        }
      }

      if (isset($validatedData['sub_menu_id'])) {
        $submenu = json_decode($validatedData['sub_menu_id']);
        $menu->subMenus()->sync($submenu);
      }

      DB::commit();
      return ResponseMessage('Menu stored successfully!', 201);
    } catch (Exception $e) {
      ResponseMessage($e->getMessage(), 500);
      throw $e;
    }
  }

  public function showMrpList($menuId, $request)
  {
    return Menu::with([
      'menu_category',
      'price',
      'menuPlaces.area',
      'menuSteps.role.department',
      'menuSteps.menuStepItem.item',
      'menuSteps.menuStepItem.uom',
      'subMenus.menuSteps.menuStepItem.item',
      'subMenus.menuSteps.menuStepItem.uom',
    ])->where('id', $menuId)
      ->where('is_active', 1)->get();
  }

  public function updateMrpList($menuId, $validatedData)
  {
    DB::beginTransaction();
    try {
      $menu = Menu::findOrFail($menuId);
      $menu->update($validatedData);
      if (isset($validatedData['image'])) {
        $imageData = $validatedData['image'];
        $extension = $imageData->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $data['image_path'] = $imageData->storeAs('menuImages/', $hashedName, 'public');
        $data['image_url'] = Storage::url($data['image_path']);
      }
      if (isset($validatedData['menu_steps'])) {
        $menuSteps = json_decode($validatedData['menu_steps']);
        $updatedMenuStepIds = [];
        foreach ($menuSteps as $step) {
          if (isset($step->id)) {
            $updatedMenuStepIds[] = $step->id;
          }
        }

        $existingMenuStepIds = $menu->menuSteps->pluck('id')->toArray();

        $menuStepsToDelete = array_diff($existingMenuStepIds, $updatedMenuStepIds);

        if (!empty($menuStepsToDelete)) {
          foreach ($menuStepsToDelete as $menuStepId) {
            $menuStep = $menu->menuSteps->find($menuStepId);
            if ($menuStep) {
              $menuStep->menuStepItem()->delete();
              $menuStep->delete();
            }
          }
        }

        foreach ($menuSteps as $step) {
          if ($step->type === "ready_to_sale") {
            if (!isset($step->expired_at) || empty($step->expired_at)) {
              return ResponseMessage("The 'expired_at' field is required for  type 'ready_to_sale'.", 402);
            }
          }
          $menuStep =  $menu->menuSteps()->updateOrCreate(
            [
              'id' => $step->id ?? null,
              'menu_id' => $menuId,
            ],
            [
              'role_id' => $step->role_id,
              'duration' => $step->duration,
              'order_time' => $step->order_time,
              'expected_quantity' => $step->expected_quantity,
              'level' => $step->level,
              'type' => $step->type,
              'expired_at' => $step->expired_at ?? null,
            ]
          );

          foreach ($step->item_menu as $itemData) {
            $quantity = ($itemData->uom_type === 'base_uom')
              ? $itemData->weight * $itemData->uom_conversion
              : $itemData->weight;
            $menuStep->menuStepItem()->updateOrCreate(
              [
                'id' => $itemData->id ?? null,
                'menu_step_id' => $menuStep->id
              ],
              [
                'item_id' => $itemData->item_id,
                'uom_id' => $itemData->uom_id,
                'weight' => $itemData->weight,
                'quantity' => $quantity,
                'uom_type' => $itemData->uom_type
              ]
            );
          }
        }
      }

      if (isset($validatedData['cooking_place_id'])) {
        $cookingPlace = json_decode($validatedData['cooking_place_id']);
        $menu->menuPlaces()->sync($cookingPlace);
        $menuCategory = MenuCategory::findOrFail($validatedData['menu_category_id']);
        foreach ($cookingPlace as $place) {
          $place = CookingPlace::findOrFail($place);
          $areaId = $place->area_id;
          $menuCategoryAreas = MenuCategoryArea::where('menu_category_id', $menuCategory->id)
            ->get();
          foreach ($menuCategoryAreas as $menuCategoryArea) {
            MenuArea::where('menu_category_area_id', $menuCategoryArea->id)
              ->update(['is_default' => 0]);
            MenuArea::updateOrCreate([
              'menu_category_area_id' => $menuCategoryArea->id,
              'cooking_area_id' =>  $areaId,
            ], [
              'menu_category_area_id' => $menuCategoryArea->id,
              'cooking_area_id' =>  $areaId,
              'is_default' => 1,
            ]);
          }
        }
      } else {
        $menu->menuPlaces()->detach();
      }

      if (isset($validatedData['sub_menu_id'])) {
        $submenu = json_decode($validatedData['sub_menu_id'] ?? '[]', true);
        if (isset($submenu)) {
          $menu->subMenus()->sync($submenu);
        } else {
          $menu->subMenus()->detach();
        }
      }
      DB::commit();
      $menuDatas = Menu::with('menuSteps.menuStepItem')->find($menu->id);

      return response()->json([
        'message' => 'Menu updated successfully!',
        'data' =>   $menuDatas
      ], 200);
    } catch (Exception $e) {
      ResponseMessage($e->getMessage(), 500);
      throw $e;
    }
  }


  public function menuToggle($menuId, $validatedData)
  {
    DB::beginTransaction();
    try {
      $menu = Menu::findOrFail($menuId);
      if (isset($validatedData['is_active'])) {
        $menu->is_active = $validatedData['is_active'];
      }
      if (isset($validatedData['is_feature'])) {
        $menu->is_feature = $validatedData['is_feature'];
      }
      $menu->save();
      DB::commit();
      ResponseMessage('Toggle has been changed');
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getMenuStepList($menuStepId)
  {
    $menu = MenuStep::with(['role.department', 'menuStepItem.item', 'menuStepItem.uom'])
      ->where('id', $menuStepId)
      ->whereHas('role.department', function ($query) {
        $query->where('name', 'kitchen');
      })
      ->get();

    return $menu;
  }

  public function menuStepItemsDelete($menuStepItemId)
  {
    $itemStepItem = MenuStepItem::findOrFail($menuStepItemId);
    $itemStepItem->delete();
  }

  public function getCookingPlace(Request $request)
  {
    return CookingPlace::with('area')->get();
  }

  public function getRoles(Request $request)
  {
    $department = Department::where('name', 'kitchen')->first();
    if (!$department) {
      return response()->json(['message' => 'Department data not found!'], 404);
    }
    return Role::where('department_id', $department->id)->with('department')->get();
  }

  public function getMenuCategoryCookingAreas(Request $request)
  {
    $sellingAreaId = $request->input('selling_area_id');
    $menuCategoriesQuery = MenuCategoryArea::with([
      'menuCategory',
      'sellingArea',
      'menuAreas.cookingArea',
    ]);

    if ($sellingAreaId) {
      $menuCategoriesQuery->where('selling_area_id', $sellingAreaId);
    }
    if ($request->has('per_page') || $request->has('page')) {
      return  $menuCategoriesQuery->paginate(config('common.list_count'));
    }
    return $menuCategoriesQuery->get();
  }

  public function updateMenuCategoryCookingAreas($menuAreaId)
  {
    DB::beginTransaction();
    try {
      $menuArea = MenuArea::findOrFail($menuAreaId);
      MenuArea::where('menu_category_area_id', $menuArea->menu_category_area_id)
        ->update(['is_default' => 0]);
      // $menuArea->is_default = $menuArea->is_default == 1 ? 0 : 1;
      $menuArea->is_default = 1;
      $menuArea->save();
      DB::commit();
      ResponseData($menuArea, 201, 'MenuCategoryArea updated successfully!');
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function getSellingAreas(Request $request)
  {
    $areaCategory = Area::with('areaCategory')
      ->where('is_active', 1)
      ->whereHas('areaCategory', function ($query) {
        $query->whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [strtolower(str_replace(' ', '', 'Selling Area'))]);
      })->get();
    ResponseData($areaCategory);
  }

  public function getMenuPrices($menuId)
  {
    $menuPrices = MenuPrice::where('menu_id', $menuId)->latest()->first();
    ResponseData($menuPrices);
  }

  public function updateMenuPrices(int $menuId, array $data)
  {
    DB::beginTransaction();
    try {
      $menuPrice = MenuPrice::create([
        'price' => $data['price'],
        'menu_id' => $menuId
      ]);
      DB::commit();
      ResponseData($menuPrice, 201, 'MenuPrice updated successfully!');
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getRemarks()
  {
    return Remark::all();
  }

  public function createRemark($data)
  {
    $remark = Remark::create($data);
    ResponseData($remark);
  }
}
