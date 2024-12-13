<?php

namespace App\Repositories\MRPForecast;

use Exception;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MrpHr;
use App\Models\KtvItem;
use App\Models\MenuStep;
use App\Models\ItemPrice;
use App\Models\MrpForecast;
use App\Models\KtvObjective;
use App\Models\SupplierItem;
use App\Models\PurchaseOrder;
use App\Models\KtvProductTree;
use App\Models\MrpRawMaterial;
use App\Services\MrpWorkingHour;
use App\Models\PurchaseOrderItem;
use App\Models\TargetMrpForecast;
use Illuminate\Support\Facades\DB;
use App\Services\AveragePriceCalculator;
use App\Http\Resources\HrForecastResource;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;

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
      'menu_step_id' => $data->menu_step_id ?? 'null',
      'item_id' => $data->item_id,
      'name' => $data->item->name ?? 'null',
      'code' => $data->item->code ?? 'null',
      'base_uom_id' => $data->item->base_uom_id,
      'base_uom_name' => $data->item->base_uom_name ?? 'null',
      'item_uom' => $data->item->item_uom ?? 'null',
      'weight' => $data->weight,
      'uom_conversion_id' => $data->item->uom_conversion_id,
      'uom_conversion' => $conversionRate,
      'uom_id' => $data->item->uom_id,
      // 'uom_name' => $data->uom->name ?? 'null',
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
        'uom_conversion_id' =>  $items->first()['uom_conversion_id'],
        'uom_conversion' => $items->first()['uom_conversion'],
        'uom_id' => $items->first()['uom_id'],
        // 'uom_name' => $items->first()['uom_name'],
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

        $latest = PurchaseOrder::orderBy('created_at', 'desc')->first();
        $count = 4;
        $no = (new CommonPurchaseOrder())->getUniqueId($latest, 'po_id', $count);
        $po_id = "PO" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;

        $po =  PurchaseOrder::create([
          'po_id' => $po_id,
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

    DB::beginTransaction();
    try {

      $mrp_forecast_id = $request->mrp_forecast_id;
      $quantity =  $request->quantity;
      $targetMenuMrp = TargetMrpForecast::where('mrp_forecastable_id', $mrp_forecastable_id)
        ->where('mrp_forecastable_type', $mrp_forecastable_type)
        ->where('mrp_forecast_id', $mrp_forecast_id)
        ->first();
      if (!$targetMenuMrp) {
        return null;
      }

      $menuId = $targetMenuMrp->mrp_forecastable_id;

      $menu = Menu::where('id', $menuId)
        ->with(['menuSteps.menuStepItem', 'subMenus.menuSteps.menuStepItem'])
        ->firstOrFail();

      $data = $this->getForcastMenusByMenuId($request, $menuId);
      $totalForecastAmount = $data['total_menu_forecast_amt'];
      $mrpForecast = $targetMenuMrp->mrpForecast;

      if ($mrpForecast) {
        $rawMaterials = $mrpForecast->MrpRawMaterials;
        $menuStepItemIds = $this->collectMenuStepItemIds($menu);

        foreach ($rawMaterials as $rawMaterial) {
          if (in_array($rawMaterial->item_id, $menuStepItemIds)) {
            $originalAmount = $rawMaterial->amount;
            $reducedAmount = $originalAmount - $totalForecastAmount;
            $rawMaterial->amount = max(0, $reducedAmount);
            $rawMaterial->save();
          } else {
            $rawMaterial->amount = $rawMaterial->amount;
          }

          $mrpHrs = $mrpForecast->MrpHrs;
          $menuStepRoleIds = $this->collectMenuStepRoleIds($menu);

          foreach ($mrpHrs  as $mrpHr) {

            if (in_array($mrpHr->role_id, $menuStepRoleIds)) {
              $menuStep = $menu->menuSteps->firstWhere('role_id', $mrpHr->role_id);

              if ($menuStep) {

                $totalHrDuration = (int)$mrpHr->total_duration;

                $menuStepDuration = (int) $menuStep->duration * $quantity;


                $durationDifference = $totalHrDuration - $menuStepDuration;
                $mrpHr->total_duration = max(0, $durationDifference);
                $mrpHr->save();
              }
            } else {
              $mrpHr->total_duration = $mrpHr->total_duration;
            }
          }
          DB::commit();
        }
      }
      $targetMenuMrp->delete();
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
  private function collectMenuStepItemIds(Menu $menu)
  {
    $menuStepItemIds = $menu->menuSteps->flatMap(function ($menuStep) {
      return $menuStep->menuStepItem->pluck('item_id');
    });


    foreach ($menu->subMenus as $subMenu) {
      $menuStepItemIds = $menuStepItemIds->merge(
        $this->collectMenuStepItemIds($subMenu)
      );
    }

    return $menuStepItemIds->unique()->toArray();
  }

  private function collectMenuStepRoleIds(Menu $menu)
  {
    $menuStepRoleIds = $menu->menuSteps->flatMap(function ($menuStep) {
      return $menuStep->pluck('role_id');
    });


    foreach ($menu->subMenus as $subMenu) {
      $menuStepRoleIds = $menuStepRoleIds->merge(
        $this->collectMenuStepRoleIds($subMenu)
      );
    }

    return $menuStepRoleIds->unique()->toArray();
  }


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

  //ktv forecast
  public function getForecastKTV($data)
  {
    $forecastKTVDatas = json_decode($data['forecast_datas'], true);
    $results = [];
    foreach ($forecastKTVDatas as $forecastKTV) {

      $ktvData =  KtvProductTree::with(['entity'])->where('entity_id',  $forecastKTV['entity_id'])->first();
      if (!$ktvData) {
        continue;
      }

      $results[] = [
        'id' => $ktvData->id,
        'entity_id' => $ktvData->entity_id,
        'name' => $ktvData->entity->name,
        'session' => $forecastKTV['session'],
        'hour' => $forecastKTV['hour'],
      ];
    }
    return $results;
  }

  public function getForecastKTVRawMaterials($data)
  {
    $inventoryId = 6;
    $result = collect();
    $forecastKTVDatas = json_decode($data['forecast_datas'], true);

    foreach ($forecastKTVDatas as $forecastKTV) {
      $quantity = $forecastKTV['session'];
      $entityId = $forecastKTV['entity_id'];

      $ktvItems = KtvItem::with([
        'item' => function ($itemQuery) use ($inventoryId) {
          $itemQuery->with([
            'balance' => function ($balanceQuery) use ($inventoryId) {
              $balanceQuery->whereHas('inventory_ledger', function ($q) use ($inventoryId) {
                $q->where('inventory_id', $inventoryId);
              });
            }
          ]);
        }
      ])
        ->whereHas('ktvProductTree', function ($query) use ($entityId) {
          $query->where('entity_id', $entityId);
        })
        ->get();

      $processedItems = $ktvItems->map(function ($ktvItem) use ($inventoryId, $entityId) {
        $item = $ktvItem->item;
        $processedData = $this->processKTVItem($ktvItem, $item, $inventoryId);
        $processedData['entity_id'] = $entityId;
        return $processedData;
      });
      $result = $result->merge($processedItems);
    }
    return $this->groupMenuStepItems($result);
  }


  private function processKTVItem($ktvItem, $item, $inventoryId)
  {

    $conversionRate = $item->uom_conversion ?? 0;
    $averagePrice = $item->average_price ?? 0;

    if ($conversionRate == 0) {
      $forecastPrice = 0;
      $uomForecastAmt = 0;
      $currentHolding = 0;
    } else {
      $totalUom = $ktvItem->quantity * $conversionRate;
      $forecastPrice = round($totalUom * round($averagePrice / $conversionRate, 4), 4);
      $uomForecastAmt = round($totalUom / $conversionRate, 4);
      $closingBalance = $balance->closing_balance ?? 0;
      $currentHolding = $closingBalance / $conversionRate;
    }

    $balance = $item->balance ?? null;
    $inBalance = $balance->in_balance ?? 0;
    $outBalance = $balance->out_balance ?? 0;


    return [
      'ktv_product_tree_id' => $ktvItem->ktv_product_tree_id,
      'item_id' => $ktvItem->item_id,
      'name' =>  $item->name ?? 'null',
      'code' => $item->code ?? 'null',
      'base_uom_id' => $item->base_uom_id,
      'base_uom_name' => $item->base_uom_name ?? 'null',
      'uom_id' => $item->uom_id,
      'item_uom' => $item->item_uom ?? 'null',
      'weight' => $ktvItem->quantity,
      'uom_conversion_id' => $item->uom_conversion_id,
      'uom_conversion' => $conversionRate,
      'total_uom_amt' => $totalUom,
      'average_price' => round($averagePrice, 4),
      'forecast_price' => $forecastPrice,
      'forecast_uom_amt' => $uomForecastAmt,
      'in_balance' => $inBalance,
      'out_balance' => $outBalance,
      'closing_balance' => $closingBalance,
      'current_holdings' => $currentHolding
    ];
  }


  public function getForecastKTVHr($data)
  {
    $forecastKTVDatas = json_decode($data['forecast_datas'], true);
    $result = collect();
    foreach ($forecastKTVDatas as $forecastKTV) {
      $quantity = $forecastKTV['session'];
      $entityId = $forecastKTV['entity_id'];

      $ktvHrData = KtvObjective::with('objectiveKey.role.department')
        ->whereHas('ktvProductTree', function ($query) use ($entityId) {
          $query->where('entity_id', $entityId);
        })
        ->get();

      $KtvHr =  $ktvHrData->map(function ($ktvHr) use ($quantity) {
        $role = $ktvHr->objectiveKey->role;
        $departmentName = $role->department->name;
        $roleId = $role->id;
        $duration = $ktvHr->objectiveKey->duration;
        $totalDuration = $duration * $quantity;
        return [
          'role_id' => $roleId,
          'department_name' => $departmentName,
          'total_duration' => $totalDuration,
        ];
      });
      $result = $result->merge($KtvHr);
    }

    return $this->groupKTVHr($result);
  }
  private function groupKTVHr($result)
  {

    return $result->groupBy('role_id')->map(function ($roles) {
      $totalDuration = $roles->sum('total_duration');
      $hours = floor($totalDuration  / 60);
      $minutes = $totalDuration  % 60;
      $totalDurationInHrs = sprintf('%02d:%02d', $hours, $minutes);
      $departmentName = $roles->first()['department_name'];

      return [
        'role_id' => $roles->first()['role_id'],
        'department_name' => $departmentName,
        'total_duration' => $totalDurationInHrs,
      ];
    })->values();
  }
}
