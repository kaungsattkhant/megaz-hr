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
            $order->total += $data['original_price'] * $data['quantity'];
            $order->update($data);
            $data['date'] = currentTime();
            $data['order_id'] = $order->id;
            $data['discount_value'] = 0;
            $data['price'] = $data['original_price'] * $data['quantity'];
            $order_items = OrderItem::create($data);
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

            return $order;

        }
    }

    public function createMultipleOrder(array $data)
    {
        $invoiceId = $data['invoice_id'];
        $order = Order::where('invoice_id',$invoiceId)->get()->first();

        foreach($data['menuArray'] as $menu)
        {
            $menu['invoice_id'] = $invoiceId;
            if ($order) {
                $order->total_quantity += $menu['quantity'];
                $order->total += $menu['original_price'] * $menu['quantity'];
                $order->update($menu);
                $menu['date'] = CurrentTime();
                $menu['order_id'] = $order->id;
                $menu['discount_value'] = 0;
                $menu['price'] = $menu['original_price'] * $menu['quantity'];
                $order_items = OrderItem::create($menu);


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

        return $order;

    }

}
