<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data)
    {
        $order = Order::where('invoice_id', $data['invoice_id'])->get()->first();
        if ($order) {
            $order->total_quantity += $data['quantity'];
            $order->total += $data['original_price'];
            $order->update($data);

            $data['date'] = currentTime();
            $data['order_id'] = $order->id;
            $data['discount_value'] = 0;
            $data['price'] = $data['original_price'] * $data['quantity'];
            $order_items = OrderItem::create($data);
            return $order->orderItems;

        } else {
            $data['date'] = currentTime();
            $data['total'] = $data['original_price'];
            $data['total_quantity'] = $data['quantity'];
            $order = Order::create($data);
            $order->update(['order_id' => sprintf('%05d', $order->id)]);

            $data['order_id'] = $order->id;
            $data['discount_value'] = 0;
            $data['price'] = $data['original_price'] * $data['quantity'];
            $order_items = OrderItem::create($data);

            return $order->orderItems;

        }
    }

}
