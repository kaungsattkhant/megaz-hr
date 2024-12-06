<?php

namespace App\Repositories\MRPForecast;




interface MRPForecastRepositoryInterface
{
  public function getForcastMenus($request, $menuId);

  public function getForcastHR($request, $menuId);

  public function getForcastRawMaterial($request, $menuId);

  public function storeForecast($data);

  public function updateForecast($data, int $mrpForecastId);

  public function getForecasts($request);

  // public function updateMenuForecast($request, $menuId);

  public function getPoForecasts($request);

  public function storePoForecastsByItemId($request, $itemId);
}
