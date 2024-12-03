<?php

namespace App\Repositories\MRPForecast;




interface MRPForecastRepositoryInterface
{

  public function getForcastMenus($request, $menuId);

  public function getForcastHR($request, $menuId);

  public function getForcastRawMaterial($request, $menuId);
}
