<?php

namespace App\Repositories\MRPForecast;

use App\Models\Menu;
use App\Models\ItemPrice;
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

    $mrpForecast =  $quantity * $menuAveragePrice;

    return [
      'total_weight' => $totalWeight,
      'total_weighted_price' => $totalWeightedPrice,
      'menu_average_price' => $menuAveragePrice,
      'menu' =>  $menu->name,
      'quantity' => $quantity,
      'amount' => $mrpForecast
    ];
  }
}
