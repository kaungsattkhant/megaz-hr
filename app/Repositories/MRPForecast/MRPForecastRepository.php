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
use PHPUnit\Framework\MockObject\Stub\ReturnStub;

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

  public function getForcastRawMaterial($request, $menuId)
  {
    $quantity = $request->quantity;
    $inventoryId = 6;
    $menu = Menu::where('id', $menuId)
      ->with([
        'subMenus',
        'menuSteps.menuStepItem' => function ($query) use ($inventoryId) {
          $query->with([
            'item' => function ($itemQuery) use ($inventoryId) {
              $itemQuery->with([
                'balance' => function ($balanceQuery) use ($inventoryId) {
                  $balanceQuery->whereHas('inventory_ledger', function ($q) use ($inventoryId) {
                    $q->where('inventory_id', $inventoryId);
                  });
                }
              ]);
            },
            'uom'
          ]);
        }
      ])
      ->first();

    if (!$menu) {
      return collect();
    }

    $menuIds = collect([$menu->id])
      ->merge($menu->subMenus->pluck('id'))
      ->toArray();

    $menuStepDatas = MenuStep::whereIn('menu_id', $menuIds)
      ->with([
        'menuStepItem'
        => function ($query) use ($inventoryId) {
          $query->with([
            'item' => function ($itemQuery) use ($inventoryId) {
              $itemQuery->with([
                'balance' => function ($balanceQuery) use ($inventoryId) {
                  $balanceQuery->whereHas('inventory_ledger', function ($q) use ($inventoryId) {
                    $q->where('inventory_id', $inventoryId);
                  });
                }
              ]);
            },
            'uom'
          ]);
        }
      ])->get();


    $result = $menuStepDatas->flatMap(function ($mStepdata) use ($quantity) {
      return $mStepdata->menuStepItem->map(function ($data) use ($quantity) {
        if (!isset($data->item)) {
          return null;
        }

        $conversionRate = $data->item->uom_conversion ?? 0;
        $average_price = $data->item->average_price ?? 0;

        $totalUom = $data->weight * (int)$quantity;
        $forecastPrice = round($totalUom * round($average_price / $conversionRate, 4), 4);
        $uomForecastAmt = round($totalUom / $conversionRate, 4);
        $balance = $data->item->balance ?? null;
        $inBalance = $balance->in_balance ?? 0;
        $outBalance = $balance->out_balance ?? 0;
        $closingBalance = $balance->closing_balance ?? 0;

        $currentHolding =  $closingBalance / $conversionRate;


        return [
          'menu_step_id' => $data->menu_step_id,
          'item_id' => $data->item_id,
          'name' => $data->item->name ?? 'null',
          'code' => $data->item->code ?? 'null',
          'base_uom_name' => $data->item->base_uom_name ?? 'null',
          'item_uom' => $data->item->item_uom ?? 'null',
          'weight' => $data->weight,
          'uom_conversion' => $conversionRate,
          'uom_name' => $data->uom->name ?? 'null',
          'total_uom_amt' => $totalUom,
          'average_price' => round($average_price, 4),
          'forecast_price' => $forecastPrice,
          'forecast_uom_amt' => $uomForecastAmt,
          'in_balance' => $inBalance,
          'out_balance' => $outBalance,
          'closing_balance' => $closingBalance,
          'current_holdings' => $currentHolding
        ];
      })->filter();
    });


    $groupedResult = $result->groupBy('item_id')->map(function ($items) {
      return [
        'item_id' => $items->first()['item_id'],
        'name' => $items->first()['name'],
        'code' => $items->first()['code'],
        'base_uom_name' => $items->first()['base_uom_name'],
        'item_uom' => $items->first()['item_uom'],
        'weight' => $items->sum('weight'),
        'uom_conversion' => $items->first()['uom_conversion'],
        'uom_name' => $items->first()['uom_name'],
        'total_uom_amt' => $items->sum('total_uom_amt'),
        'average_price' => $items->sum(function ($item) {
          return $item['average_price'];
        }),
        'forecast_price' => $items->sum('forecast_price'),
        'forecast_uom_amt' => $items->sum('forecast_uom_amt'),
        'in_balance' => $items->sum(function ($item) {
          return $item['in_balance'];
        }),
        'out_balance' => $items->sum(function ($item) {
          return $item['out_balance'];
        }),
        'closing_balance' => $items->sum(function ($item) {
          return $item['closing_balance'];
        }),
        'current_holdings' => $items->sum(function ($item) {
          return $item['current_holdings'];
        }),
      ];
    })->values();

    return $groupedResult;
  }
}



 //here is update , 
//  $menu = Menu::where('id', $menuId)
//  ->with([
//      'subMenus',
//      'menuSteps.menuStepItem' => function ($query) use ($inventoryId) {
//          $query->with([
//              'item' => function ($itemQuery) use ($inventoryId) {
//                  $itemQuery->with([
//                      'balance' => function ($balanceQuery) use ($inventoryId) {
//                          $balanceQuery->whereHas('inventory_ledger', function ($q) use ($inventoryId) {
//                              $q->where('inventory_id', $inventoryId);
//                          });
//                      }
//                  ]);
//              },
//              'uom'
//          ]);
//      }
//  ])
//  ->first();
//  //
//  return $menu;
