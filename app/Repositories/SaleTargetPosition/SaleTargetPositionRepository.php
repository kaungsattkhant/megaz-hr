<?php

namespace App\Repositories\SaleTargetPosition;

use App\Models\SaleTargetPosition;
use App\Models\TargetPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleTargetPositionRepository implements SaleTargetPositionRepositoryInterface
{
    public function createSaleTarget(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $saleTargetPosition = SaleTargetPosition::create($data);
            if (isset($data['target_positions'])) {
                $targetPositions = json_decode($data['target_positions'], true);
                foreach ($targetPositions as $targetPosition) {
                    TargetPosition::create([
                        'role_id' => $targetPosition['role_id'],
                        'amount' => $targetPosition['amount'],
                        'sale_target_position_id' => $saleTargetPosition->id,
                    ]);
                }
            }

            DB::commit();
            ResponseMessage($saleTargetPosition);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }

    public function updateSaleTarget(Request $request, int $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $saleTargetPosition = SaleTargetPosition::find($id);
            $saleTargetPosition->update($data);
            if (isset($data['target_positions'])) {
                $targetPositions = json_decode($data['target_positions'], true);

                $roleIds = array_column($targetPositions, 'role_id');
                foreach ($targetPositions as $targetPosition) {
                    TargetPosition::updateOrCreate(
                        [
                            'sale_target_position_id' => $saleTargetPosition->id,
                            'role_id' => $targetPosition['role_id']
                        ],
                        ['amount' => $targetPosition['amount']]
                    );
                }

                TargetPosition::where('sale_target_position_id', $saleTargetPosition->id)
                    ->whereNotIn('role_id', $roleIds)
                    ->delete();
            } else {
                TargetPosition::where('sale_target_position_id', $saleTargetPosition->id)->delete();
            }

            DB::commit();
            ResponseData($saleTargetPosition);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteSaleTarget(int $id)
    {
        DB::beginTransaction();
        try {
            $saleTargetPosition = SaleTargetPosition::find($id);
            $saleTargetPosition->delete();
            DB::commit();
            ResponseMessage('Sale Target Position has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function saleTargetPositionList()
    {
        $saleTargetPositions = SaleTargetPosition::orderBy('created_at', 'desc')
        ->leftJoin(DB::raw('(SELECT sale_target_position_id, SUM(amount) as total_amount FROM target_positions GROUP BY sale_target_position_id) as target_position_sums'), 'sale_target_positions.id', '=', 'target_position_sums.sale_target_position_id')
        ->select('sale_target_positions.*', 'target_position_sums.total_amount')
        ->with('department')
        ->paginate(config('common.list_count'));
        ResponseData($saleTargetPositions);
    }

    public function saleTargetPositionDetail(int $id)
    {
        $saleTargetPosition = SaleTargetPosition::with('targetPositions','department')->find($id);
        ResponseData($saleTargetPosition);
    }
}
