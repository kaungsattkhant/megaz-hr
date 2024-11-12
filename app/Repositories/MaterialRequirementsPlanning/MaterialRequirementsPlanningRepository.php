<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Exception;
use App\Models\Menu;
use App\Models\MenuStep;
use App\Models\MenuPrice;
use App\Models\MenuStepItem;
use App\Models\SubMenu;
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
      'menuSteps.staff.roles.department',
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
      // if (isset($validatedData['image'])) {
      //   $imageData = $validatedData['image'];
      //   $extension = $imageData->getClientOriginalExtension();
      //   $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
      //   $data['image_path'] = $imageData->storeAs('menuImages/', $hashedName, 'public');
      //   $data['image_url'] = Storage::url($data['image_path']);
      // }

      $menu = Menu::create([
        'name' => $validatedData['name'],
        'menu_category_id' => $validatedData['menu_category_id'],
        'code' => $validatedData['code'],
        'description' => $validatedData['description']
      ]);

      if (!empty($validatedData['menu_steps'])) {

        foreach ($validatedData['menu_steps'] as $data) {
          $menuStep = MenuStep::create([
            'menu_id' => $menu->id,
            'staff_id' => $data['staff_id'],
            'staff_quantity' => $data['staff_quantity'],
            'duration' => $data['duration'],
            'order_time' => $data['order_time'],
            'expected_quantity' => $data['expected_quantity'],
            'level' => $data['level'],
            'type' => $data['type']
          ]);

          if (!empty($data['item_menu'])) {
            foreach ($data['item_menu'] as $itemData) {

              MenuStepItem::create([

                'menu_step_id' => $menuStep->id,
                'item_id' => $itemData['item_id'],
                'uom_id' => $itemData['uom_id'],
                'weight' => $itemData['weight']
              ]);
            }
          }
        }
      }

      if (!empty($validatedData['price'])) {
        MenuPrice::create([
          'menu_id' => $menu->id,
          'price' => $validatedData['price']
        ]);
      }

      if (!empty($validatedData['cooking_place_id'])) {
        $menu->menuPlaces()->sync($validatedData['cooking_place_id']);
      }

      if (!empty($validatedData['sub_menu_id'])) {
        $menu->subMenus()->sync($validatedData['sub_menu_id']);
      }

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
      'menuSteps.staff.roles.department',
      'menuSteps.menuStepItem.item',
      'menuSteps.menuStepItem.uom',
      'subMenus.menuSteps.menuStepItem.item',
      'subMenus.menuSteps.menuStepItem.uom',
    ])->where('id', $menuId)
      ->where('is_active', 1)
      ->whereHas('menuSteps.staff', function ($query) {
        $query->where('is_active', 1);
      })->get();
  }

  public function updateMrpList($menuId, $validatedData)
  {
    DB::beginTransaction();
    try {
      $menu = Menu::findOrFail($menuId);
      $menu->update($validatedData);
      // if (isset($validatedData['image'])) {
      //   $imageData = $validatedData['image'];
      //   $extension = $imageData->getClientOriginalExtension();
      //   $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
      //   $data['image_path'] = $imageData->storeAs('menuImages/', $hashedName, 'public');
      //   $data['image_url'] = Storage::url($data['image_path']);
      // }
      foreach ($menu->menuSteps as $menuStep) {
        $menuStep->menuStepItem()->delete();
      }
      $menu->menuSteps()->delete();

      if (!empty($validatedData['menu_steps'])) {

        foreach ($validatedData['menu_steps'] as $data) {
          $menuStep =  $menu->menuSteps()->updateOrCreate(
            [
              'id' => $data['id'] ?? null,
              'menu_id' => $menuId,
            ],
            [
              'staff_id' => $data['staff_id'],
              'staff_quantity' => $data['staff_quantity'],
              'duration' => $data['duration'],
              'order_time' => $data['order_time'],
              'expected_quantity' => $data['expected_quantity'],
              'level' => $data['level'],
              'type' => $data['type']
            ]
          );

          if (!empty($data['item_menu'])) {
            foreach ($data['item_menu'] as $itemData) {
              $menuStep->menuStepItem()->updateOrCreate(
                [
                  'id' => $itemData['id'] ?? null,
                  'menu_step_id' => $menuStep->id
                ],
                [
                  'item_id' => $itemData['item_id'],
                  'uom_id' => $itemData['uom_id'],
                  'weight' => $itemData['weight']
                ]
              );
            }
          }
        }
      }

      if (!empty($validatedData['cooking_place_id'])) {
        $menu->menuPlaces()->sync($validatedData['cooking_place_id']);
      }

      if (!empty($validatedData['sub_menu_id'])) {
        $menu->subMenus()->sync($validatedData['sub_menu_id']);
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
    $menu = MenuStep::with(['staff.roles.department', 'menuStepItem.item', 'menuStepItem.uom'])
      ->where('id', $menuStepId)
      ->whereHas('staff', function ($query) {
        $query->where('is_active', 1);
      })
      ->get();

    return $menu;
  }

  public function menuStepItemsDelete($menuStepItemId)
  {
    $itemStepItem = MenuStepItem::findOrFail($menuStepItemId);
    $itemStepItem->delete();
  }
}
