<?php

namespace App\Repositories\MRPForecast;

use App\Models\Menu;
use App\Models\MenuStep;
use App\Models\ItemPrice;
use App\Models\SupplierItem;
use App\Services\MrpWorkingHour;
use Illuminate\Support\Facades\DB;
use App\Services\AveragePriceCalculator;
use App\Http\Resources\HrForecastResource;

class MRPForecastRepository implements MRPForecastRepositoryInterface
{
  protected $averagePriceCalculator;
  protected MrpWorkingHour $MrpWorkingHour;
  public function __construct(AveragePriceCalculator $repo, MrpWorkingHour $MrpWorkingHour)
  {
    $this->averagePriceCalculator = $repo;
    $this->MrpWorkingHour = $MrpWorkingHour;
  }


  public function getForcastHR($request, $menuId)
  {
    $quantity = $request->quantity;
    $hrDurations = $this->MrpWorkingHour->getGroupedHrDurations($menuId, $quantity);
    return  $hrDurations;
  }


  public function getForcastMenus($request, $menuId)
  {
    $quantity = $request->quantity;
    $menu = Menu::where('id', $menuId)
      ->with(['menuSteps.menuStepItem.item', 'subMenus.menuSteps.menuStepItem.item'])
      ->first();

    if (!$menu) {
      return ['error' => 'Menu not found'];
    }
    $totals = $this->calculateMenuTotals($menu);

    $mrpForecast = $quantity * $totals['totalWeightedPrice'];
    $result = [
      'id' => $menu->id,
      'menu' => $menu->name,
      'quantity' => $quantity,
      'total_menu_forecast_amt' => $mrpForecast,
    ];

    return $result;
  }

  private function calculateMenuTotals($menu)
  {
    $totalWeightedPrice = 0;
    $totalWeight = 0;

    $menuStepItems = $menu->menuSteps
      ->flatMap(function ($menuStep) {
        return $menuStep->menuStepItem;
      });


    foreach ($menuStepItems as $menuStepItem) {
      $item = $menuStepItem->item;

      $averagePrice = $this->averagePriceCalculator->getAveragePriceForItem($item->id);
      $weight = $menuStepItem->weight ?? 0;

      $totalWeightedPrice += $averagePrice * $weight;
      $totalWeight += $weight;
    }

    foreach ($menu->subMenus as $subMenu) {
      $subMenuTotals = $this->calculateMenuTotals($subMenu);
      $totalWeightedPrice += $subMenuTotals['totalWeightedPrice'];
      $totalWeight += $subMenuTotals['totalWeight'];
    }

    return [
      'totalWeightedPrice' => $totalWeightedPrice,
      'totalWeight' => $totalWeight
    ];
  }
}
