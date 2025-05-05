<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Allowance;
use App\Models\TimeShift;
use App\Models\DayInOffDay;
use App\Models\SalarySetup;
use App\Models\OffDayAssignment;
use Illuminate\Support\Facades\DB;

class MrpWorkingHour
{
  public function getGroupedHrDurations($menuId, $quantity, $date)
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
    $roleIds = $menuSteps->pluck('role_id')->unique()->toArray();
    // $menuSteps = $menuSteps->map(function ($data) {

    //   $totalMinutes = (int)$data->total_working_hour;

    //   $hours = floor($totalMinutes / 60);
    //   $minutes = $totalMinutes % 60;
    //   $data->total_working_hour = sprintf('%02d:%02d', $hours, $minutes);

    //   return $data;
    // });


    $pricePerHr = $this->getPricePerHr($roleIds, $date);

    $menuSteps = $menuSteps->map(function ($step) use ($pricePerHr) {
      $totalMinutes = (int)$step->total_working_hour;  // Convert to total working minutes

      // Calculate the cost
      $cost = ($totalMinutes / 60) * $pricePerHr;  // Price per hour * total working hours

      return [
        'role_id' => $step->role_id,
        'position' => $step->position,
        'total_working_hour' => $totalMinutes,
        'hr_cost_cal' => $cost
      ];
    });
    return $menuSteps;
  }


  public function getPricePerHr($roleIds, $date)
  {
    $month = $date->month;
    $year = $date->year;
    $totalDaysInMonth = $date->daysInMonth;
    $publicHolidays = $this->calculatePublicHolidays($date);
    $salary = $this->calculateSalaryByRole($roleIds);
    if (!$salary) {
      return collect();
    }
    $staffIds = Staff::whereHas('roles', function ($query) use ($roleIds) {
      $query->whereIn('roles.id', $roleIds);
    })->pluck('id')->unique()->toArray();

    $depIds = Role::whereIn('id', $roleIds)
      ->with(['department'])
      ->pluck('department_id')->toArray();

    $offDayAssignments = OffDayAssignment::where(function ($query) use ($staffIds) {
      $query->whereIn('offdayable_id', $staffIds)  // Use whereIn for staff IDs
        ->where('offdayable_type', 'staff');
    })
      ->orWhere(function ($query) use ($depIds) {
        $query->whereIn('offdayable_id', $depIds)
          ->where('offdayable_type', 'department');
      })
      ->with('offDay.days')
      ->get();
    $offDayCount = 0;
    foreach ($offDayAssignments as $assignment) {
      $offDay = $assignment->offDay;

      foreach ($offDay->days as $day) {
        $dayName = $day->day;
        $currentDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $currentDate->copy()->endOfMonth();
        $weekCounter = 0;
        while ($currentDate <= $endDate) {

          if ($currentDate->englishDayOfWeek === $dayName) {
            if ($offDay->repetition === 'Bi-weekly') {
              if ($weekCounter % 2 === 0) {
                $offDayCount++;
              }
              $weekCounter++;
            } elseif ($offDay->repetition === 'Weekly') {
              $offDayCount++;
            } elseif ($offDay->repetition === 'Monthly') {
              if ($currentDate->day <= 7) {
                $offDayCount++;
              }
            }
          }
          $currentDate->addDay();
        }
      }
    }

    $totalWorkHrInADay = (new TimeShift())->calculateTotalShiftDuration();
    $acutalWorkDays = $totalDaysInMonth - $publicHolidays - $offDayCount;
    $pricePerDay = abs((float)$salary / $acutalWorkDays);
    $pricePerHr = abs((float)$salary / ($acutalWorkDays * $totalWorkHrInADay));
    return  $pricePerHr;
  }

  public function calculateSalaryByRole($roleIds)
  {
    $salarySetup = SalarySetup::whereIn('role_id', $roleIds)->first();
    if (!$salarySetup) {
      return null;
    }
    $basicSalary = $salarySetup->basic_salary;
    $allowances = Allowance::where('role_id', $roleIds)->get();
    $totalAllowance = 0;
    foreach ($allowances as $allowance) {

      $totalAllowance += $allowance->amount;
    }
    $totalSalary = $basicSalary + $totalAllowance;
    return $totalSalary;
  }

  public function calculatePublicHolidays($date)
  {
    $startDate = $date->copy()->startOfMonth();
    $endDate = $date->copy()->endOfMonth();
    $publicHolidays = DayInOffDay::whereBetween('date', [$startDate, $endDate])
      ->where('day', 'Public-holiday')
      ->count();
    return $publicHolidays;
  }
}
