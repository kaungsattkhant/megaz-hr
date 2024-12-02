<?php

namespace App\Repositories\MRPForecast;

use App\Http\Resources\HrForecastResource;
use App\Models\Menu;
use App\Models\ItemPrice;
use App\Models\MenuStep;
use App\Models\SupplierItem;

class MRPForecastRepository implements MRPForecastRepositoryInterface
{

  public function getForcastMenus($request, $menuId)
  {
    $quantity = $request->quantity;

    $menu = Menu::where('id', $menuId)
      ->with(['menuSteps.menuStepItem.item'])
      ->first();

    if (!$menu) {
      return ['error' => 'Menu not found'];
    }

    $menuStepItems = $menu->menuSteps
      ->flatMap(function ($menuStep) {
        return $menuStep->menuStepItem;
      });
    $totalWeightedPrice = 0;
    $totalWeight = 0;

    foreach ($menuStepItems as $menuStepItem) {
      $item = $menuStepItem->item;

      $supplierItems = SupplierItem::where('item_id', $item->id)->pluck('id');


      $averagePrice = ItemPrice::whereIn('supplier_item_id', $supplierItems)->avg('price');


      $weight = $menuStepItem->weight ?? 0;
      $totalWeightedPrice += $averagePrice * $weight;
      $totalWeight += $weight;
    }


    $menuAveragePrice = $totalWeight > 0 ? $totalWeightedPrice / $totalWeight : 0;

    $mrpForecast =  $quantity * $totalWeightedPrice;

    return [
      'total_weight' => $totalWeight,
      'total_weighted_price' => $totalWeightedPrice,
      'menu_average_price' => $menuAveragePrice,
      'menu' =>  $menu->name,
      'quantity' => $quantity,
      'amount' => $mrpForecast
    ];
  }

  public function getForcastHR($request, $menuId)
  {
    $data = MenuStep::where('menu_id', $menuId)->get();

    return HrForecastResource::collection($data);
  }


  // public function getForcastMenus($request, $menuId)
  // {
  //   $quantity = $request->quantity;

  //   // Fetch the menu with steps, step items, and submenus
  //   $menu = Menu::where('id', $menuId)
  //     ->with(['menuSteps.menuStepItem.item', 'subMenus.menuSteps.menuStepItem.item'])
  //     ->first();


  //   if (!$menu) {
  //     return ['error' => 'Menu not found'];
  //   }

  //   // Helper function to collect items recursively from submenus
  //   $collectItemsFromMenu = function ($menu) use (&$collectItemsFromMenu) {
  //     $menuStepItems = $menu->menuSteps->flatMap(function ($menuStep) {
  //       return $menuStep->menuStepItem;
  //     });


  //     $items = $menuStepItems->map(function ($menuStepItem) {
  //       return [
  //         'item_id' => $menuStepItem->item->id,
  //         'weight' => $menuStepItem->weight ?? 0,
  //       ];
  //     });

  //     // Traverse submenus recursively
  //     foreach ($menu->subMenus as $subMenu) {
  //       $items = $items->merge($collectItemsFromMenu($subMenu));
  //     }

  //     return $items;
  //   };


  //   // Collect all items, including from submenus
  //   $allItems = $collectItemsFromMenu($menu);

  //   $totalWeightedPrice = 0;
  //   $totalWeight = 0;

  //   foreach ($allItems as $menuStepItem) {
  //     $itemId = $menuStepItem['item_id'];
  //     $weight = $menuStepItem['weight'];

  //     $supplierItems = SupplierItem::where('item_id', $itemId)->pluck('id');
  //     $averagePrice = ItemPrice::whereIn('supplier_item_id', $supplierItems)->avg('price') ?? 0;

  //     $totalWeightedPrice += $averagePrice * $weight;
  //     $totalWeight += $weight;
  //   }

  //   $menuAveragePrice = $totalWeight > 0 ? round($totalWeightedPrice / $totalWeight, 2)  : 0;
  //   $mrpForecast = round($quantity * $totalWeightedPrice, 2);

  //   return [
  //     'total_weight' => $totalWeight,
  //     'total_weighted_price' => $totalWeightedPrice,
  //     'menu_average_price' => (float) $menuAveragePrice,
  //     'menu' => $menu->name,
  //     'quantity' => $quantity,
  //     'amount' => $mrpForecast,
  //   ];
  // }
}
