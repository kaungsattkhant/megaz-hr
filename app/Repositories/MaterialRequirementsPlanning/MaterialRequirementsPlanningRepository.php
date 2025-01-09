<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Exception;
use App\Models\Menu;
use App\Models\Role;
use App\Models\SubMenu;
use App\Models\MenuStep;
use App\Models\MenuPrice;
use App\Models\Department;
use App\Models\CookingPlace;
use App\Models\MenuStepItem;
use Illuminate\Http\Request;
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
        'description' => $validatedData['description']
      ]);

      // if (!empty($validatedData['menu_steps'])) {
      $menuSteps = json_decode($validatedData['menu_steps']);

      foreach ($menuSteps as $data) {
        $menuStep = MenuStep::create([
          'menu_id' => $menu->id,
          'role_id' => $data->role_id,
          'duration' => $data->duration,
          'order_time' => $data->order_time,
          'expected_quantity' => $data->expected_quantity,
          'level' => $data->level,
          'type' => $data->type
        ]);

        // if (!empty($data['item_menu'])) {
        foreach ($data->item_menu as $itemData) {

          MenuStepItem::create([

            'menu_step_id' => $menuStep->id,
            'item_id' => $itemData->item_id,
            'uom_id' => $itemData->uom_id,
            'weight' => $itemData->weight
          ]);
        }
        // }
      }
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
      $menuDatas = Menu::with('menuSteps.menuStepItem')->find($menu->id);

      return response()->json([
        'message' => 'Menu stored successfully!',
        'data' =>   $menuDatas
      ], 201);
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
      $menu->subMenus()->detach();
      // if (!empty($validatedData['menu_steps'])) {
      $menuSteps = json_decode($validatedData['menu_steps']);

      foreach ($menuSteps as $data) {
        $menuStep =  $menu->menuSteps()->updateOrCreate(
          [
            'id' => $data->id ?? null,
            'menu_id' => $menuId,
          ],
          [
            'role_id' => $data->role_id,
            'duration' => $data->duration,
            'order_time' => $data->order_time,
            'expected_quantity' => $data->expected_quantity,
            'level' => $data->level,
            'type' => $data->type
          ]
        );

        // if (!empty($data['item_menu'])) {
        foreach ($data->item_menu as $itemData) {
          $menuStep->menuStepItem()->updateOrCreate(
            [
              'id' => $itemData->id ?? null,
              'menu_step_id' => $menuStep->id
            ],
            [
              'item_id' => $itemData->item_id,
              'uom_id' => $itemData->uom_id,
              'weight' => $itemData->weight
            ]
          );
        }
        // }
      }
      // }

      $cookingPlace = json_decode($validatedData['cooking_place_id']);
      // if (!empty($validatedData['cooking_place_id'])) {
      $menu->menuPlaces()->sync($cookingPlace);
      // }

      // if (!empty($validatedData['sub_menu_id'])) {
      $submenu = json_decode($validatedData['sub_menu_id']);
      $menu->subMenus()->sync($submenu);
      // }

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
}
