<?php

namespace App\Repositories\MRPForecast;

use Illuminate\Http\Request;

interface MRPForecastRepositoryInterface
{
  public function getForcastMenusByMenuId($request, $menuId);

  public function getForcastMenus($data);

  public function getForcastHrByMenuId($request, $menuId);

  public function getForcastHR($data);

  public function getForcastRawMaterialByMenuId($request, $menuId);

  public function getForcastRawMaterial($data);

  public function storeForecast($data);

  public function updateMenuForecast($data, int $mrpForecastId);

  public function getMonthlyMenuForecasts(Request $request);

  public function getMonthlyKTVProductTreeForecasts(Request $request);

  public function getMonthlyMenuForecastsById($mrpForecastId);

  public function getPoForecasts($request);

  public function storePoForecastsByItemId($request, $itemId);

  public function deleteMenuForecast($request, $target_mrp_forecast_id);

  public function getForecastKTV($data);
  public function getForecastKTVByEntityId($data, $entityId);

  public function getForecastKTVRawMaterials($data);
  public function getForecastKTVRawMaterialsByEntityId($data, $entityId);

  public function getForecastKTVHr($data);
  public function getForecastKTVHrByEntityId($data, $entityId);
}
