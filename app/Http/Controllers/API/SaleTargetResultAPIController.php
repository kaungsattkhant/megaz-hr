<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SaleTargetMenu;
use App\Models\SaleTargetPosition;
use App\Models\TargetMenuResult;
use App\Models\TargetPositionResult;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleTargetResultAPIController extends Controller
{
    public function getSaleTargetResult(Request $request)
    {
        // Get the role ID of the user
        $role = UserData()->roles->first();
        $roleId = $role->id;

        // Handle the 'month' query parameter (format: YYYY-MM)
        $currentMonth = $request->get('month', Carbon::now()->format('Y-m')); // Default to current month
        $parsedMonth = Carbon::createFromFormat('Y-m', $currentMonth);
        $year = $parsedMonth->year;
        $month = $parsedMonth->month;

        // Get target amount for the specified month and role
        $targetAmount = SaleTargetPosition::whereYear('month', $year)
            ->whereMonth('month', $month)
            ->whereHas('targetPositions', function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            })
            ->with(['targetPositions' => function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            }])
            ->get()
            ->pluck('targetPositions')
            ->collapse()
            ->sum('amount');

        // Get total amount from TargetPositionResult for the specified month and role
        $totalAmount = TargetPositionResult::whereYear('date_time', $year)
            ->whereMonth('date_time', $month)
            ->where('role_id', $roleId)
            ->sum('amount');

        // Handle specific departments
        if (in_array(UserData()->department_id, [5, 7, 8])) {
            // Get target menus for the specified month
            $targetMenus = SaleTargetMenu::whereYear('month', $year)
                ->whereMonth('month', $month)
                ->with(['targetMenus' => function ($query) {
                    $query->select('id', 'sale_target_menu_id', 'menu_id', 'quantity');
                }])
                ->get()
                ->pluck('targetMenus')
                ->collapse()
                ->keyBy('menu_id');

            // Get sold menus for the specified month
            $soldMenus = TargetMenuResult::whereYear('date_time', $year)
                ->whereMonth('date_time', $month)
                ->with('menu')
                ->get()
                ->keyBy('menu_id');

            // Combine target and sold menu results
            $allMenus = $soldMenus->union($targetMenus)->map(function ($menu, $menuId) use ($targetMenus, $soldMenus) {
                return [
                    'menu' => $soldMenus[$menuId]->menu ?? $targetMenus[$menuId]->menu,
                    'target_quantity' => $targetMenus[$menuId]->quantity ?? null,
                    'sold_quantity' => $soldMenus[$menuId]->quantity ?? null,
                ];
            });
        } else {
            $allMenus = collect(); // Empty for other departments
        }

        // Prepare response data
        $responseData = [
            'target_position_result' => [
                'target_amount' => $targetAmount,
                'current_month_amount' => $totalAmount,
                'role_name' => $role->name,
                'role_id' => $role->id,
            ],
            'target_menu_result' => $allMenus->values()->all(),
        ];

        // Return response
        ResponseData($responseData);
    }


}
