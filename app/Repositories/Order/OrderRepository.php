<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $price = $data['original_price'] * $data['quantity'];
            $order = Order::where('invoice_id', $data['invoice_id'])->get()->first();
            if ($order) {
                $order->total_quantity += $data['quantity'];
                $order->total += $data['original_price'] * $data['quantity'];
                $order->update($data);
                $originalOrderItem = OrderItem::where('menu_id', $data['menu_id'])->where('order_id', $order->id)->get()->first();
                if ($originalOrderItem) {
                    $originalOrderItem->quantity += $data['quantity'];
                    $originalOrderItem->price += $data['original_price'] * $data['quantity'];
                    $originalOrderItem->save();
                } else {
                    $data['date'] = currentTime();
                    $data['order_id'] = $order->id;
                    $data['discount_value'] = 0;
                    $data['price'] = $data['original_price'] * $data['quantity'];
                    $order_items = OrderItem::create($data);
                }
                DB::commit();
                return $order;
            } else {
                $data['date'] = currentTime();
                $data['total'] = $data['original_price'] * $data['quantity'];
                $data['total_quantity'] = $data['quantity'];
                $order = Order::create($data);
                $order->update(['order_id' => sprintf('%05d', $order->id)]);

                $data['order_id'] = $order->id;
                $data['discount_value'] = 0;
                $data['price'] = $data['original_price'] * $data['quantity'];
                $order_items = OrderItem::create($data);
                DB::commit();
                return $order;
            }
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createMultipleOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $invoiceId = $data['invoice_id'];
            $order = Order::where('invoice_id', $invoiceId)->get()->first();
            $categorySums = [];
            foreach ($data['menuArray'] as $menu) {
                $menuCategoryId = $menu['menu_category_id'];
                $price = $menu['original_price'] * $menu['quantity'];

                if (!isset($categorySums[$menuCategoryId])) {
                    $categorySums[$menuCategoryId] = 0;
                }

                $categorySums[$menuCategoryId] += $price;
                $menu['invoice_id'] = $invoiceId;
                if ($order) {
                    $order->total_quantity += $menu['quantity'];
                    $order->total += $menu['original_price'] * $menu['quantity'];
                    $order->update($menu);

                    $originalOrderItem = OrderItem::where('menu_id', $menu['menu_id'])->where('order_id', $order->id)->get()->first();
                    if ($originalOrderItem) {
                        $originalOrderItem->quantity += $menu['quantity'];
                        $originalOrderItem->price += $menu['original_price'] * $menu['quantity'];
                        $originalOrderItem->save();
                    } else {
                        $menu['date'] = CurrentTime();
                        $menu['order_id'] = $order->id;
                        $menu['discount_value'] = 0;
                        $menu['price'] = $menu['original_price'] * $menu['quantity'];
                        $order_items = OrderItem::create($menu);
                    }
                } else {
                    $menu['date'] = CurrentTime();
                    $menu['total'] = $menu['original_price'] * $menu['quantity'];
                    $menu['total_quantity'] = $menu['quantity'];
                    $order = Order::create($menu);
                    $order->update(['order_id' => sprintf('%05d', $order->id)]);

                    $menu['order_id'] = $order->id;
                    $menu['discount_value'] = 0;
                    $menu['price'] = $menu['original_price'] * $menu['quantity'];
                    $order_items = OrderItem::create($menu);
                }
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
