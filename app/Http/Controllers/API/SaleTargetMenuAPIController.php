<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SaleTargetMenu;
use App\Models\SaleTargetPosition;
use App\Models\TargetMenuResult;
use App\Models\TargetPosition;
use App\Models\TargetPositionResult;
use App\Repositories\SaleTargetMenu\SaleTargetMenuRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleTargetMenuAPIController extends Controller
{
    //
    protected $saleTargetMenuRepo;

    public function __construct(SaleTargetMenuRepositoryInterface $saleTargetMenuRepo)
    {
        $this->saleTargetMenuRepo = $saleTargetMenuRepo;
    }

    public function listSaleTargetMenu()
    {
        $this->saleTargetMenuRepo->listSaleTargetMenu();
    }

    public function getSaleTargetMenu(int $id)
    {
        $this->saleTargetMenuRepo->getSaleTargetMenu($id);
    }

    public function createSaleTargetMenu(Request $request)
    {
        $this->saleTargetMenuRepo->createSaleTargetMenu($request);
    }

    public function updateSaleTargetMenu(Request $request, int $id)
    {
        $this->saleTargetMenuRepo->updateSaleTargetMenu($request, $id);
    }

    public function deleteSaleTargetMenu(int $id)
    {
        $this->saleTargetMenuRepo->deleteSaleTargetMenu($id);
    }

    public function getSaleTargetResult()
    {
        $role = UserData()->roles->first();
        $roleId = $role->id;
        $currentMonth = Carbon::now()->month;

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

            if(UserData()->department_id == 5 || UserData()->department_id == 7 || UserData()->department_ud==8)
            {
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
            }else{
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
                'current_menu' => $currentSaleMenu,
            ],
        ];

        ResponseData($responseData);
    }
}
