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

  public function getMonthlyMenuForecasts($request);

  public function getMonthlyMenuForecastsById($mrpForecastId);

  public function getPoForecasts($request);

  public function storePoForecastsByItemId($request, $itemId);

  public function deleteMenuForecast($request, $mrp_forecastable_id, $mrp_forecastable_type);

  public function deleteMonthlyMenuForecast($forecastId);
}
