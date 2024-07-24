<?php

namespace App\Repositories\FoodOrder;

use App\Models\FoodOrder;
use App\Models\FoodOrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodOrderRepository implements FoodOrderRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();
        $foodOrders = FoodOrder::with('customer', 'address', 'confirmedBy', 'cancelledBy')
            ->whereBetween('date_time', [$validateDate . ' 00:00:00', $validateDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
        ResponseData($foodOrders);
    }

    public function createFoodOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $data['date_time'] = CurrentTime();
            $foodOrder = FoodOrder::create($data);
            $foodOrderItem = json_decode($data['food_order_items'], true);
            foreach ($foodOrderItem as $order) {
                FoodOrderItem::create([
                    'food_order_id' => $foodOrder->id,
                    'menu_id' => $order['menu_id'],
                    'quantity' => $order['quantity'],
                    'discount_price' => $order['discount_price'],
                    'original_price' => $order['original_price'],
                    'menu_service_discount_id' => $order['menu_service_discount_id'] ?? null,
                ]);
            }

            DB::commit();
            ResponseMessage('Food Order Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function confirmFoodOrder(int $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $foodOrder = FoodOrder::find($id);
            if ($request->is_confirm == 1) {
                $foodOrder->area_id = $request->area_id;
                $foodOrder->confirmed_by = UserData()->id;
                $foodOrder->confirmed_at = CurrentTime();
                $foodOrder->save();
            } else {
                $foodOrder->cancelled_by = UserData()->id;
                $foodOrder->cancelled_at = CurrentTime();
                $foodOrder->save();
            }
            DB::commit();
            ResponseData($foodOrder);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }
}
