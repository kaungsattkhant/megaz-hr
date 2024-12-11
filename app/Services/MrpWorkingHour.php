<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MrpWorkingHour
{
  public function getGroupedHrDurations($menuId, $quantity)
  {

    $menu = Menu::where('id', $menuId)
      ->with(['subMenus'])
      ->first();

    if (!$menu) {
      return collect();
    }

    $menuIds = collect([$menu->id])
      ->merge($menu->subMenus->pluck('id'))
      ->toArray();

    $menuSteps = DB::table('menu_steps')
      ->join('roles', 'menu_steps.role_id', '=', 'roles.id')
      ->join('departments', 'roles.department_id', '=', 'departments.id')
      ->whereIn('menu_steps.menu_id', $menuIds)
      ->select(
        'menu_steps.role_id',
        DB::raw("CONCAT(roles.name, ' (', departments.name, ')') as position"),
        DB::raw("SUM(menu_steps.duration * $quantity) as total_working_hour")
      )
      ->groupBy('menu_steps.role_id', 'roles.name', 'departments.name')
      ->get();

    $menuSteps = $menuSteps->map(function ($data) {

      $totalMinutes = (int)$data->total_working_hour;

      $hours = floor($totalMinutes / 60);
      $minutes = $totalMinutes % 60;
      $data->total_working_hour = sprintf('%02d:%02d', $hours, $minutes);

      return $data;
    });


    return $menuSteps;
  }
}
