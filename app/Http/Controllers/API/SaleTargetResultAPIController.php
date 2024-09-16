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
    //
    public function getSaleTargetResuls(Request $request)
    {
        $role = UserData()->roles->first();
        $roleId = $role->id;
        $currentMonth = $request->has('month') ? request()->month : Carbon::now()->month;

        $targetAmount = SaleTargetPosition::whereMonth('month', $currentMonth)
            ->with(['targetPositions' => function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            }])
            ->get()
            ->pluck('targetPositions')
            ->collapse()
            ->sum('amount');

        $totalAmount = TargetPositionResult::whereMonth('date_time', $currentMonth)
            ->where('role_id', $roleId)
            ->sum('amount');

        if (UserData()->department_id == 5 || UserData()->department_id == 7 || UserData()->department_ud == 8) {
            $targetMenuQuantity = SaleTargetMenu::whereMonth('month', $currentMonth)
                ->with(['targetMenus' => function ($query) {
                    $query->select('id', 'sale_target_menu_id', 'menu_id', 'quantity');
                }])
                ->get()
                ->map(function ($saleTargetMenu) {
                    return [
                        'id' => $saleTargetMenu->id,
                        'month' => $saleTargetMenu->month,
                        'target_menus' => $saleTargetMenu->targetMenus->map(function ($targetMenu) {
                            return [
                                'quantity' => $targetMenu->quantity,
                                'menu_id' => $targetMenu->menu_id,
                                'menu' => $targetMenu->menu,
                            ];
                        }),
                    ];
                });

            $currentSaleMenu = TargetMenuResult::whereMonth('date_time', $currentMonth)
                ->with('menu')
                ->get()
                ->map(function ($targetMenuResult) {
                    return [
                        'quantity' => $targetMenuResult->quantity,
                        'menu_id' => $targetMenuResult->menu_id,
                        'menu' => $targetMenuResult->menu,
                    ];
                });
        } else {
            $targetMenuQuantity = null;
            $currentSaleMenu = null;
        }

        $responseData = [
            'target_position_result' => [
                'target_amount' => $targetAmount,
                'current_month_amount' => $totalAmount,
                'role_name' => $role->name,
                'role_id' => $role->id,
            ],
            'target_menu_result' => [
                'target_menu' => $targetMenuQuantity,
                'current_sold_menu' => $currentSaleMenu,
            ],
        ];

        ResponseData($responseData);
    }

    public function getSaleTargetResult(Request $request)
    {
        $role = UserData()->roles->first();
        $roleId = $role->id;
        $currentMonth = $request->has('month') ? request()->month : Carbon::now()->month;

        $targetAmount = SaleTargetPosition::whereMonth('month', $currentMonth)
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

        $totalAmount = TargetPositionResult::whereMonth('date_time', $currentMonth)
            ->where('role_id', $roleId)
            ->sum('amount');

        if (in_array(UserData()->department_id, [5, 7, 8])) {
            $targetMenus = SaleTargetMenu::whereMonth('month', $currentMonth)
                ->with(['targetMenus' => function ($query) {
                    $query->select('id', 'sale_target_menu_id', 'menu_id', 'quantity');
                }])
                ->get()
                ->pluck('targetMenus')
                ->collapse()
                ->keyBy('menu_id');

            $soldMenus = TargetMenuResult::whereMonth('date_time', $currentMonth)
                ->with('menu')
                ->get()
                ->keyBy('menu_id');

            $allMenus = $soldMenus->union($targetMenus)->map(function ($menu, $menuId) use ($targetMenus, $soldMenus) {
                return [
                    'menu' => $soldMenus[$menuId]->menu ?? $targetMenus[$menuId]->menu,
                    'target_quantity' => $targetMenus[$menuId]->quantity ?? null,
                    'sold_quantity' => $soldMenus[$menuId]->quantity ?? null,
                ];
            });
        } else {
            $allMenus = collect();
        }


        $responseData = [
            'target_position_result' => [
                'target_amount' => $targetAmount,
                'current_month_amount' => $totalAmount,
                'role_name' => $role->name,
                'role_id' => $role->id,
            ],
            'target_menu_result' => $allMenus->values()->all(),
        ];

        ResponseData($responseData);
    }

}
