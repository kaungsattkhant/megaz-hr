<?php

namespace App\Repositories\MRPForecast;

use Exception;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MrpHr;
use App\Models\MenuStep;
use App\Models\ItemPrice;
use App\Models\MrpForecast;
use App\Models\SupplierItem;
use App\Models\PurchaseOrder;
use App\Models\MrpRawMaterial;
use App\Services\MrpWorkingHour;
use App\Models\PurchaseOrderItem;
use App\Models\TargetMrpForecast;
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


  public function getForcastHrByMenuId($request, $menuId)
  {
    $quantity = $request->quantity;
    $hrDurations = $this->MrpWorkingHour->getGroupedHrDurations($menuId, $quantity);
    return  $hrDurations;
  }

  public function getForcastHR($data)
  {
    $forecastMenuDatas = json_decode($data['forecast_datas'], true);

    $allHrDurations = collect();
    foreach ($forecastMenuDatas as $forecastMenuData) {
      $hrDurations = $this->MrpWorkingHour->getGroupedHrDurations($forecastMenuData['menu_id'], $forecastMenuData['quantity']);
      $allHrDurations = $allHrDurations->merge($hrDurations);
    }
    $groupedHrDurations = $allHrDurations->groupBy('role_id')->map(function ($roles) {
      $first = $roles->first();
      $totalMinutes = $roles->reduce(function ($carry, $role) {
        [$hours, $minutes] = explode(':', $role->total_working_hour);
        return $carry + ($hours * 60) + $minutes;
      }, 0);

      $hours = floor($totalMinutes / 60);
      $minutes = $totalMinutes % 60;

      return [
        'role_id' => $first->role_id,
        'position' => $first->position,
        'total_working_hour' => sprintf('%02d:%02d', $hours, $minutes),
      ];
    })->values();

    return $groupedHrDurations;
  }

  public function getForcastMenusByMenuId($request, $menuId)
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

  public function getForcastMenus($data)
  {
    $forecastMenuDatas = json_decode($data['forecast_datas'], true);
    $results = [];
    foreach ($forecastMenuDatas as $forecastMenuData) {
      $menu = Menu::where('id', $forecastMenuData['menu_id'])
        ->with(['menuSteps.menuStepItem.item', 'subMenus.menuSteps.menuStepItem.item'])
        ->first();

      if (!$menu) {
        return ['error' => 'Menu not found'];
      }
      $totals = $this->calculateMenuTotals($menu);

      $mrpForecast = $forecastMenuData['quantity'] * $totals['totalWeightedPrice'];
      $results[] = [
        'id' => $menu->id,
        'menu' => $menu->name,
        'quantity' => $forecastMenuData['quantity'],
        'total_menu_forecast_amt' => $mrpForecast,
      ];
    }
    return $results;
  }


  public function getForcastRawMaterialByMenuId($request, $menuId)
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
        return $this->processMenuStepItem($data, $quantity);
      })->filter();
    });

    return $this->groupMenuStepItems($result);
  }

  public function getForcastRawMaterial($data)
  {
    $forecastMenuDatas = json_decode($data['forecast_datas'], true);

    $result = collect();


    foreach ($forecastMenuDatas as $forecastData) {
      $menuId = $forecastData['menu_id'];
      $quantity = $forecastData['quantity'];


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
        continue;
      }


      $menuIds = collect([$menu->id])
        ->merge($menu->subMenus->pluck('id'))
        ->toArray();


      $menuStepDatas = MenuStep::whereIn('menu_id', $menuIds)
        ->with([
          'menuStepItem' => function ($query) use ($inventoryId) {
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
        ->get();

      $menuStepDataProcessed = $menuStepDatas->flatMap(function ($mStepdata) use ($quantity) {
        return $mStepdata->menuStepItem->map(function ($data) use ($quantity) {
          return $this->processMenuStepItem($data, $quantity);
        })->filter();
      });

      $result = $result->merge($menuStepDataProcessed);
    }

    return $this->groupMenuStepItems($result);
  }

  private function processMenuStepItem($data, $quantity)
  {
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

    $currentHolding = $closingBalance / $conversionRate;

    return [
      'menu_step_id' => $data->menu_step_id,
      'item_id' => $data->item_id,
      'name' => $data->item->name ?? 'null',
      'code' => $data->item->code ?? 'null',
      'base_uom_id' => $data->item->base_uom_id,
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
  }

  private function groupMenuStepItems($result)
  {
    return $result->groupBy('item_id')->map(function ($items) {
      return [
        'item_id' => $items->first()['item_id'],
        'name' => $items->first()['name'],
        'code' => $items->first()['code'],
        'base_uom_id' =>  $items->first()['base_uom_id'],
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
  }


  public function storeForecast($data)
  {
    DB::beginTransaction();
    try {
      $mrpForecast = MrpForecast::create($data);

      if (isset($data['target_mrp'])) {

        $targetMrps = json_decode($data['target_mrp'], true);

        foreach ($targetMrps as $targetMrp) {

          TargetMrpForecast::create([
            'mrp_forecast_id' => $mrpForecast->id,
            'mrp_forecastable_id' => $targetMrp['mrp_forecastable_id'],
            'mrp_forecastable_type' => $targetMrp['mrp_forecastable_type'],
            'quantity' => $targetMrp['quantity'],
            'amount' => $targetMrp['amount'],
            'hour' => $targetMrp['hour'] ?? null,
          ]);
        }
      }

      $forecastHrs = json_decode($data['forecast_hr'], true);
      foreach ($forecastHrs as  $forecastHr) {

        MrpHr::create([
          'mrp_forecast_id' => $mrpForecast->id,
          'role_id' =>  $forecastHr['role_id'],
          'total_duration' => $forecastHr['total_duration'],
        ]);
      }


      $forecastRaws = json_decode($data['forecast_raw'], true);
      foreach ($forecastRaws as  $forecastRaw) {

        MrpRawMaterial::create([
          'mrp_forecast_id' => $mrpForecast->id,
          'item_id' => $forecastRaw['item_id'],
          'uom_id' => $forecastRaw['uom_id'],
          'quantity' => $forecastRaw['quantity'],
          'amount' => $forecastRaw['amount'],
        ]);
      }

      DB::commit();
      return $mrpForecast;
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function updateMenuForecast($data, int $mrpForecastId)
  {
    DB::beginTransaction();
    try {
      $mrpForecast = MrpForecast::findOrFail($mrpForecastId);

      $mrpForecast->update($data);

      if (isset($data['target_mrp'])) {

        $targetMrps = json_decode($data['target_mrp'], true);
        foreach ($targetMrps as $targetMrp) {
          $targetMrpData = TargetMrpForecast::find($targetMrp['id']);
          if ($targetMrpData) {
            $targetMrpData->update([
              'mrp_forecast_id' => $mrpForecast->id,
              'mrp_forecastable_id' => $targetMrp['mrp_forecastable_id'],
              'mrp_forecastable_type' => $targetMrp['mrp_forecastable_type'],
              'quantity' => $targetMrp['quantity'],
              'amount' => $targetMrp['amount'],
              'hour' => $targetMrp['hour'] ?? null,
            ]);
          }
        }
      }
      $forecastHrs = json_decode($data['forecast_hr'], true);
      foreach ($forecastHrs as  $forecastHr) {
        $mrpHrdata = MrpHr::find($forecastHr['id']);
        if ($mrpHrdata) {
          $mrpHrdata->update([
            'mrp_forecast_id' => $mrpForecast->id,
            'role_id' =>  $forecastHr['role_id'],
            'total_duration' => $forecastHr['total_duration'],
          ]);
        }
      }


      $forecastRaws = json_decode($data['forecast_raw'], true);
      foreach ($forecastRaws as  $forecastRaw) {
        $mrpRawData =  MrpRawMaterial::find($forecastRaw['id']);
        if ($mrpRawData) {
          $mrpRawData->update([
            'mrp_forecast_id' => $mrpForecast->id,
            'item_id' => $forecastRaw['item_id'],
            'uom_id' => $forecastRaw['uom_id'],
            'quantity' => $forecastRaw['quantity'],
            'amount' => $forecastRaw['amount'],
          ]);
        }
      }

      DB::commit();
      return $mrpForecast;
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }


  public function getMonthlyMenuForecasts($request)
  {
    $inventoryId = 6;
    return MrpForecast::with([
      'targetMrpForecasts' => function ($q) {
        $q->where('mrp_forecastable_type', 'menu');
      },
      'targetMrpForecasts.mrp_forecastable',
      'MrpHrs.role',
      'MrpRawMaterials.item' => function ($itemQuery) use ($inventoryId) {
        $itemQuery->with([
          'balance' => function ($balanceQuery) use ($inventoryId) {
            $balanceQuery->whereHas('inventory_ledger', function ($q) use ($inventoryId) {
              $q->where('inventory_id', $inventoryId);
            });
          }
        ]);
      },
      'MrpRawMaterials.uom'
    ])->get();
  }


  public function getMonthlyMenuForecastsById($mrpForecastId)
  {
    $inventoryId = 6;
    return MrpForecast::with([
      'targetMrpForecasts' => function ($q) {
        $q->where('mrp_forecastable_type', 'menu');
      },
      'targetMrpForecasts.mrp_forecastable',
      'MrpHrs.role',
      'MrpRawMaterials.item' => function ($itemQuery) use ($inventoryId) {
        $itemQuery->with([
          'balance' => function ($balanceQuery) use ($inventoryId) {
            $balanceQuery->whereHas('inventory_ledger', function ($q) use ($inventoryId) {
              $q->where('inventory_id', $inventoryId);
            });
          }
        ]);
      },
      'MrpRawMaterials.uom'
    ])->where('id', $mrpForecastId)->get();
  }

  public function getPoForecasts($request)
  {
    return PurchaseOrder::where('status', 'created')->get();
  }

  public function storePoForecastsByItemId($data, $itemId)
  {

    $averagePrice = $this->averagePriceCalculator->getAveragePriceForItem($itemId);
    $totalPrice = $data['quantity'] * $averagePrice;

    if (isset($data['status'])) {
      DB::beginTransaction();
      try {

        $po =  PurchaseOrder::create([
          'po_id' => $data['po_id'],
          'total_price' => $totalPrice,
          'date' => now()->format('Y-m-d'),
          'created_by' => UserData()->id,
          'status' => $data['status']
        ]);
        $poItem = PurchaseOrderItem::create([
          'quantity' => $data['quantity'],
          'purchase_order_id' => $po->id,
          'item_id' => $itemId,
          'amount' => $data['quantity'],
          'original_quantity' => $data['quantity'],
          'uom_id' => $data['uom_id'],
          'uom_conversion_id' => $data['uom_conversion_id']
        ]);
        DB::commit();
        return $poItem;
      } catch (Exception $e) {
        DB::rollBack();
        throw $e;
      }
    } else {

      $po =  PurchaseOrder::findOrFail($data['purchase_order_id']);
      $po->increment('total_price', $totalPrice);
      return PurchaseOrderItem::create(
        [
          'quantity' => $data['quantity'],
          'purchase_order_id' => $po->id,
          'item_id' => $itemId,
          'amount' => $data['quantity'],
          'original_quantity' => $data['quantity'],
          'uom_id' => $data['uom_id'],
          'uom_conversion_id' => $data['uom_conversion_id']
        ]
      );
    }
  }


  public function deleteMenuForecast($request, $mrp_forecastable_id, $mrp_forecastable_type)
  {

    // DB::beginTransaction();
    // try {

    //   $mrp_forecast_id = $request->mrp_forecast_id;

    //   $targetMenuMrp = TargetMrpForecast::where('mrp_forecastable_id', $mrp_forecastable_id)
    //     ->where('mrp_forecastable_type', $mrp_forecastable_type)
    //     ->where('mrp_forecast_id', $mrp_forecast_id)
    //     ->firstOrFail();

    //   $menuId = $targetMenuMrp->mrp_forecastable_id;

    //   $menu = Menu::where('id', $menuId)
    //     ->with(['menuSteps.menuStepItem', 'subMenus.menuSteps.menuStepItem'])
    //     ->firstOrFail();


    //   $data = $this->getForcastMenusByMenuId($request, $menuId);

    //   $hrData  = $this->getForcastHrByMenuId($request, $menuId);

    //   return $hrData;
    //   $totalForecastAmount = $data['total_menu_forecast_amt'];

    //   $roles = $menu->menuSteps->pluck('role_id')->unique();
    //   $mrpForecast = $targetMenuMrp->mrpForecast;

    //   if ($mrpForecast) {
    //     $totalRawAmt = 0;
    //     $menuStepItemIds = $this->collectMenuStepItemIds($menu);
    //     $rawMaterials = $mrpForecast->MrpRawMaterials;

    //     $reducedAmounts = [];

    //     foreach ($rawMaterials as $rawMaterial) {
    //       if (in_array($rawMaterial->item_id, $menuStepItemIds)) {

    //         $originalAmount = $rawMaterial->amount;
    //         $reducedAmount = $originalAmount - $totalForecastAmount;
    //         $rawMaterial->amount = max(0, $reducedAmount);


    //         $rawMaterial->save();


    //         $reducedAmounts[] = [
    //           'item_id' => $rawMaterial->item_id,
    //           'original_amount' => $originalAmount,
    //           'reduced_amount' => $rawMaterial->amount,
    //         ];
    //       } else {

    //         $totalRawAmt += $rawMaterial->amount;
    //       }
    //     }



    //     // Reduce durations for roles


    //     $mrpHrs = $mrpForecast->MrpHrs;
    //     $reducedDurations = [];

    //     foreach ($hrData['data'] as $hrEntry) {
    //       $roleId = $hrEntry['role_id'];
    //       $totalWorkingHour = $hrEntry['total_working_hour'];


    //       $durationToReduce = $this->convertDurationToMinutes($totalWorkingHour);


    //       $mrpHr = $mrpHrs->firstWhere('role_id', $roleId);

    //       if ($mrpHr) {
    //         $originalDuration = $mrpHr->total_duration;
    //         $reducedDuration = max(0, $originalDuration - $durationToReduce);

    //         $mrpHr->total_duration = $reducedDuration;
    //         $mrpHr->save();

    //         $reducedDurations[] = [
    //           'role_id' => $roleId,
    //           'original_duration' => $originalDuration,
    //           'reduced_duration' => $reducedDuration,
    //         ];
    //       }
    //     }




    //     DB::commit();
    //     return [
    //       'total_raw_amount' => $totalRawAmt,
    //       'total_forecast_amount' => $totalForecastAmount,
    //       'reducedAmounts' => $reducedAmounts,
    //     ];
    //   }
    // } catch (Exception $e) {
    //   DB::rollBack();
    //   throw $e;
    // }
  }
  // private function collectMenuStepItemIds(Menu $menu)
  // {
  //   $menuStepItemIds = $menu->menuSteps->flatMap(function ($menuStep) {
  //     return $menuStep->menuStepItem->pluck('item_id');
  //   });


  //   foreach ($menu->subMenus as $subMenu) {
  //     $menuStepItemIds = $menuStepItemIds->merge(
  //       $this->collectMenuStepItemIds($subMenu)
  //     );
  //   }

  //   return $menuStepItemIds->unique()->toArray();
  // }

  // private function convertDurationToMinutes($duration)
  // {
  //   list($hours, $minutes) = explode(':', $duration);
  //   return ($hours * 60) + $minutes;
  // }


  public function deleteMonthlyMenuForecast($forecastId)
  {
    $mrpMenuForecast = MrpForecast::findOrFail($forecastId);
    $mrpMenuForecast->targetMrpForecasts()->delete();
    $mrpMenuForecast->MrpHrs()->delete();
    $mrpMenuForecast->MrpRawMaterials()->delete();
    $mrpMenuForecast->delete();
  }
}
