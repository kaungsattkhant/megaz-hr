<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SaleTargetMenu;
use App\Models\TargetPosition;
use App\Models\TargetMenuResult;
use App\Models\SaleTargetPosition;
use App\Http\Controllers\Controller;
use App\Models\TargetPositionResult;

class SaleTargetResultAPIController extends Controller
{
    public function getSaleTargetResult(Request $request)
    {
        if (!UserData()) {
            ResponseMessage('Unauthenticated', 401);
        }
        $role = UserData()->roles->first();
        $roleId = $role->id;

        // $currentMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $currentMonth = Carbon::now()->format('Y') . '-' . $request->month;
        $parsedMonth = Carbon::createFromFormat('Y-m', $currentMonth);
        $year = $parsedMonth->year;
        $month = $parsedMonth->month;

        $totals = \App\Models\TargetPosition::query()
            ->join('sale_target_positions as stp', 'target_positions.sale_target_position_id', '=', 'stp.id')
            ->where('target_positions.role_id', $roleId)
            ->whereYear('stp.month', $year)
            ->selectRaw('SUM(target_positions.amount) as total_target_amount, SUM(stp.head_count) as total_head_count')
            ->first();

        $targetResults = TargetPositionResult::whereYear('date_time', $year)
            ->join('invoices', 'target_position_results.invoice_id', 'invoices.id')
            ->join('head_counts', 'invoices.head_count_id', 'head_counts.id')
            ->whereMonth('date_time', $month)
            ->where('role_id', $roleId)
            ->selectRaw('SUM(target_position_results.amount ) as total_result_amount,SUM(head_counts.total_head_count) as total_result_count')
            ->first();

        if (in_array(UserData()->department_id, [5, 7, 8])) {
            $targetMenus = SaleTargetMenu::whereYear('month', $year)
                ->whereMonth('month', $month)
                ->with([
                    'targetMenus' => function ($query) use ($request) {
                        $query->select('id', 'sale_target_menu_id', 'menu_id', 'quantity');
                        if ($request->area_id) {
                            $query->where('area_id', $request->area_id);
                        }
                    }
                ])
                ->get()
                ->pluck('targetMenus')
                ->collapse()
                ->keyBy('menu_id');

            $soldMenus = TargetMenuResult::whereYear('date_time', $year)
                ->whereMonth('date_time', $month)
                ->when($request->area_id, function ($query) use ($request) {
                    return $query->where('area_id', $request->area_id);
                })
                ->with('menu')
                ->get()
                ->keyBy('menu_id');
            $allMenus = $soldMenus->union($targetMenus)->map(function ($menu, $menuId) use ($targetMenus, $soldMenus) {
                return [
                    'menu' => $soldMenus[$menuId]->menu ?? $targetMenus[$menuId]->menu,
                    'target_quantity' => $targetMenus[$menuId]->quantity ?? 0,
                    'sold_quantity' => $soldMenus[$menuId]->quantity ?? 0,
                ];
            });
        } else {
            $allMenus = collect();
        }

        $responseData = [
            'target_position_result' => [
                'target_amount' => $totals->total_target_amount,
                'current_month_amount' => $targetResults->total_result_amount,
                'taget_head_count' => $totals->total_head_count,
                'total_head_count' => $targetResults->total_result_count,
                'role_name' => $role->name,
                'role_id' => $role->id,
            ],
            'target_menu_result' => $allMenus->values()->all(),
        ];

        ResponseData($responseData);
    }


}
