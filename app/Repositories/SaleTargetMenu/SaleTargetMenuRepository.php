<?php

namespace App\Repositories\SaleTargetMenu;

use App\Models\SaleTargetMenu;
use App\Models\TargetMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleTargetMenuRepository implements SaleTargetMenuRepositoryInterface
{
    public function listSaleTargetMenu()
    {
        $saleTargetMenu = SaleTargetMenu::with('targetMenus')
        ->leftJoin(DB::raw('(SELECT sale_target_menu_id, SUM(quantity) as total_quantity FROM target_menus GROUP BY sale_target_menu_id) as target_menu_sums'), 'sale_target_menus.id', '=', 'target_menu_sums.sale_target_menu_id')
        ->select('sale_target_menus.*', 'target_menu_sums.total_quantity')
        ->orderBy('created_at', 'desc')
        ->paginate(config('common.list_count'));

        ResponseData($saleTargetMenu);
    }

    public function getSaleTargetMenu(int $id)
    {
        $saleTargetMenu = SaleTargetMenu::with('targetMenus.menu','targetMenus.area')->find($id);
        ResponseData($saleTargetMenu);
    }

    public function createSaleTargetMenu(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $saleTargetMenu = SaleTargetMenu::create($data);
            if (isset($data['target_menus'])) {
                $targetMenus = json_decode($request->target_menus);
                foreach ($targetMenus as $targetMenu) {
                    TargetMenu::create([
                        'menu_id' => $targetMenu->menu_id, // Use -> to access properties
                        'quantity' => $targetMenu->quantity,
                        'sale_target_menu_id' => $saleTargetMenu->id,
                        'area_id' => $targetMenu->area_id
                    ]);
                }

            }
            DB::commit();
            ResponseData($saleTargetMenu);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateSaleTargetMenu(Request $request, int $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $saleTargetMenu = SaleTargetMenu::find($id);
            $saleTargetMenu->update($data);
            if (isset($data['target_menus'])) {
                $targetMenus = json_decode($request->target_menus);
                $menuIds = array_column($targetMenus, 'menu_id');

                foreach ($targetMenus as $targetMenu) {
                    TargetMenu::updateOrCreate(
                        [
                            'menu_id' => $targetMenu->menu_id,             // Correct for objects
                            'sale_target_menu_id' => $saleTargetMenu->id,
                            'area_id' => $targetMenu->area_id
                        ],
                        [
                            'quantity' => $targetMenu->quantity,
                        ]
                    );

                }

                TargetMenu::where('sale_target_menu_id', $saleTargetMenu->id)
                    ->whereNotIn('menu_id', $menuIds)
                    ->delete();
            } else {
                TargetMenu::where('sale_target_menu_id', $saleTargetMenu->id)->delete();
            }
            DB::commit();
            ResponseData($saleTargetMenu);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteSaleTargetMenu(int $id)
    {
        DB::beginTransaction();
        try {
            $saleTargetMenu = SaleTargetMenu::find($id);
            $saleTargetMenu->delete();
            DB::commit();
            ResponseMessage('Sale Target Menu has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
