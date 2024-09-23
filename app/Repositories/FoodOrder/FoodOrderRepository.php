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
        $foodOrders = FoodOrder::where('status', '!=', 'cancelled')->with([
            'customer',
            'customer.addresses' => function ($query) {
                $query->where('is_default', 1);
            },
            'confirmedBy',
            'cancelledBy',
            'foodOrderItems' => function ($query) {
                $query->where('status', '!=', 'cancelled')
                    ->with('menu.areas');
            }
        ])
            ->whereBetween('date_time', [$validateDate . ' 00:00:00', $validateDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        ResponseData($foodOrders);
    }

    public function foodOrderListForKitchen(Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();
        $foodOrders = FoodOrder::where('status', '=', 'confirmed')->with([
            'customer',
            'customer.addresses' => function ($query) {
                $query->where('is_default', 1);
            },
            'confirmedBy',
            'cancelledBy',
            'foodOrderItems' => function ($query) {
                $query->where('status', '=', 'confirmed')
                    ->with('menu.areas');
            }
        ])
            ->whereBetween('date_time', [$validateDate . ' 00:00:00', $validateDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        ResponseData($foodOrders);
    }


    public function confirmFoodOrderItem(int $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $foodOrderItem = FoodOrderItem::find($id);
            if ($request->is_confirm == 1) {
                $foodOrderItem->area_id = $request->area_id;
                $foodOrderItem->status = 'confirmed';
                $foodOrderItem->save();
            } else {
                $foodOrderItem->status = 'cancelled';
                $foodOrderItem->save();
            }

            DB::commit();
            ResponseData($foodOrderItem);
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
                $foodOrder->status = 'confirmed';
                $foodOrder->confirmed_by = UserData()->id;
                $foodOrder->confirmed_at = CurrentTime();
                $foodOrder->save();
            } else {
                $foodOrder->status = 'cancelled';
                $foodOrder->cancelled_by = UserData()->id;
                $foodOrder->cancelled_at = CurrentTime();
                $foodOrder->save();
            }

            DB::commit();
            Responsemessage('Food Order Status changed successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function createConfirmFoodOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['date_time'] = CurrentTime();
            $data['confirmed_time'] = CurrentTime();
            $data['status'] = 'confirmed';
            $foodOrder = FoodOrder::create($data);
            $foodOrderItem = json_decode($data['food_order_items'], true);
            foreach ($foodOrderItem as $order) {
                FoodOrderItem::create([
                    'food_order_id' => $foodOrder->id,
                    'menu_id' => $order['menu_id'],
                    'quantity' => $order['quantity'],
                    'discount_price' => $order['discount_price'],
                    'original_price' => $order['original_price'],
                    'status' => 'confirmed',
                    'area_id' => $order['area_id'],
                    'menu_service_discount_id' => $order['menu_service_discount_id'] ?? null,
                ]);
            }

            DB::commit();
            ResponseData($foodOrder);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function updateFoodOrderStatus(int $id,Request $request)
    {
        DB::beginTransaction();
        try{
            $foodOrder = FoodOrder::find($id);
            if($request->status == 'kitchen_confirmed')
            {
                $foodOrder->status = 'kitchen_confirmed';
                $foodOrder->kitchen_confirmed_at = CurrentTime();
                $foodOrder->save();
            }else if($request->status == 'done')
            {
                $foodOrder->status = 'done';
                $foodOrder->done_at = CurrentTime();
                $foodOrder->save();
            }else{
                ResponseMessage('Invalid Status', 422);
            }
            DB::commit();
            ResponseData($foodOrder);
        }catch(\Exception $e){
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    // user app
    public function createFoodOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $data['customer_id'] = UserData()->id;
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
}
