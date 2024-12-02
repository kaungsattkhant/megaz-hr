<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MrpWorkingHour
{

  public function calculateHrDuration($menu, $quantity)
  {
    $data = [];

    foreach ($menu->menuSteps as $menuStep) {
      $roleDuration = $menuStep->duration * $quantity;
      $workHour = gmdate('H:i:s',  $roleDuration);

      $data[] = [
        'id' => $menuStep->id,
        'menu_id' => $menuStep->menu_id,
        'role_id' => $menuStep->role_id,
        'position' => $menuStep->role->name ?? null,
        'duration' =>   $workHour
      ];
    }
    foreach ($menu->subMenus as $subMenu) {
      $subMenuData = $this->calculateHrDuration($subMenu, $quantity);
      $data = array_merge($data, $subMenuData);
    }
    return $data;
  }
}
