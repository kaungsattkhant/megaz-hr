<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Exception;
use App\Models\Menu;
use App\Models\Role;
use App\Models\SubMenu;
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
    $category = $request->input('category');
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
    ])->paginate(config('common.list_count'));
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
      // if (!empty($validatedData['menu_steps'])) {
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

        // if (!empty($step['item_menu'])) {
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
      // }
      // }

      if (!empty($validatedData['price'])) {
        MenuPrice::create([
          'menu_id' => $menu->id,
          'price' => $validatedData['price']
        ]);
      }

      $cookingPlace = json_decode($validatedData['cooking_place_id']);
      // if (!empty($validatedData['cooking_place_id'])) {
      $menu->menuPlaces()->sync($cookingPlace);
      // }

      // if (!isset($validatedData['sub_menu_id'])) {
      $submenu = json_decode($validatedData['sub_menu_id']);
      // if (!empty($validatedData['sub_menu_id'])) {
      $menu->subMenus()->sync($submenu);
      // }

      DB::commit();
      // $menuDatas = Menu::with('menuSteps.menuStepItem')->find($menu->id);

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
      foreach ($menu->menuSteps as $menuStep) {
        $menuStep->menuStepItem()->delete();
      }
      $menu->menuSteps()->delete();
      // if (!empty($validatedData['menu_steps'])) {
      $menuSteps = json_decode($validatedData['menu_steps']);

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

        // if (!empty( $step['item_menu'])) {
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
              'weight' => $itemData->weight,
              'uom_type' => $itemData->uom_type
            ]
          );
        }
        // }
      }
      // }

      $cookingPlace = json_decode($validatedData['cooking_place_id']);
      if (!empty($cookingPlace)) {
        $menu->menuPlaces()->sync($cookingPlace);
      } else {
        $menu->menuPlaces()->detach();
      }

      $submenu = json_decode($validatedData['sub_menu_id'] ?? '[]', true);
      if (!empty($submenu)) {
        $menu->subMenus()->sync($submenu);
      } else {
        $menu->subMenus()->detach();
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
        $menu->is_active = $menu->is_active ? 0 : 1;
      }
      if (isset($validatedData['is_feature'])) {
        $menu->is_feature = $menu->is_feature ? 0 : 1;
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
    $menuCategoriesQuery = MenuCategory::with('areas.areaCategory');

    if ($request->has('area_category_id')) {
      $areaCategoryId = $request->input('area_category_id');

      $menuCategoriesQuery->whereHas('areas', function ($query) use ($areaCategoryId) {
        $query->where('area_category_id', $areaCategoryId);
      });
    }
    return $menuCategoriesQuery->get();
  }

  public function createMenuCategoryCookingAreas($request)
  {
    DB::beginTransaction();
    try {
      $data = $request->all();
      if (isset($data['id'])) {
        $menuCategoryArea = MenuCategoryArea::updateOrCreate(
          ['id' => $data['id']],
          $data
        );
      } else {
        $menuCategoryArea = MenuCategoryArea::create($data);
      }
      DB::commit();
      ResponseData($menuCategoryArea, 201, 'MenuCategoryArea saved successfully!');
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function getAreaCategories(Request $request)
  {
    $areaCategory = AreaCategory::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [strtolower(str_replace(' ', '', 'Selling Area'))])->get();
    ResponseData($areaCategory);
  }
}
