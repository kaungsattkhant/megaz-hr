<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\RoomSession;
use Illuminate\Support\Facades\DB;
use App\Events\KitchenNotificationRequest;
use App\Events\WaiterOrderConfirmNotificationRequest;

class OrderService
{
    public function createOrder(array $data)
    {
        // DB::beginTransaction();
        // try {
            $price = $data['original_price'] * $data['quantity'];
            // $data['invoice'] must be unsigned integer format , not 000023
            $order = Order::where('invoice_id', $data['invoice_id'])->first();
            if(!$order){
                ResponseMessage('Order not found',404);
            }
            $menu = Menu::find($data['menu_id']);
            if(!$menu){
                ResponseMessage('Menu not found',404);
            }
            $latestMenuServiceDiscount = $menu->menuServiceDiscounts()
                ->whereDate('from_date', '<=', CurrentDate())
                ->whereDate('to_date', '>=', CurrentDate())
                ->orderBy('created_at', 'desc')
                ->where('type', 'menu')
                ->first();
            if ($latestMenuServiceDiscount) {
                $discountAmount = $latestMenuServiceDiscount->discount_price * $data['quantity'];
                $data['menu_service_discount_id'] = $latestMenuServiceDiscount->id;
                $data['discount_value'] = $latestMenuServiceDiscount->discount_price * $data['quantity'];
            } else {
                $discountAmount = 0;
            }
            if ($order) {
                $order->total_quantity += $data['quantity'];
                $order->total_discount_price += $discountAmount;
                $order->total += $data['original_price'] * $data['quantity'];
                $order->save();

                $data['date'] = currentTime();
                $data['order_id'] = $order->id;
                $data['price'] = $data['original_price'] * $data['quantity'];
                $order_items = OrderItem::create($data);
                $orderItems = OrderItem::find($order_items->id);
                $order_items->menu = $order_items->menu;

                $invoice = Invoice::find($data['invoice_id']);
                $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
                $entity = Entity::find($latestRoomSession->entitySession->entity_id);
                broadcast(new KitchenNotificationRequest($entity, $order, null, $orderItems, 7));
                DB::commit();
                return $order;
            } else {
                $data['date'] = currentTime();
                $data['total'] = $data['original_price'] * $data['quantity'];
                $data['total_quantity'] = $data['quantity'];
                $data['total_discount_price'] = $discountAmount;
                $order = Order::create($data);
                $order->update(['order_id' => sprintf('%05d', $order->id)]);

                $data['order_id'] = $order->id;
                $data['price'] = $data['original_price'] * $data['quantity'];
                $order_items = OrderItem::create($data);
                $orderItems = OrderItem::find($order_items->id);

                $order_items->menu = $order_items->menu;

                $invoice = Invoice::find($data['invoice_id']);
                $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
                $entity = Entity::find($latestRoomSession->entitySession->entity_id);
                broadcast(new KitchenNotificationRequest($entity, $order, null, $orderItems, 7));
                // DB::commit();
                return $order;
            }
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     ResponseMessage($e->getMessage(), 402);
        //     throw $e;
        // }
    }
    public function createMultipleOrder(array $data)
    {
        $invoiceId = $data['invoice_id'];
        $order = Order::where('invoice_id', $invoiceId)->first();
        $categorySums = [];
        $totalDiscount = 0;
        $invoice = Invoice::find($data['invoice_id']);
        $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
        $entity = Entity::find($latestRoomSession->entitySession->entity_id);

        $orderItemsArray = [];
        $focTotal = 0;
        foreach ($data['menuArray'] as $menuData) {
            $menuData['area_id']=2;
            if(!isset($menuData['area_id']) || $menuData['area_id']==null){
                ResponseMessage('Area is required',419);
            }
            $menuData['invoice_id'] = $invoiceId;
            $menu = Menu::find($menuData['menu_id']);
            if (!isset($menuData['discount_value'])) {
                $latestMenuServiceDiscount = $menu->menuServiceDiscounts()
                    ->whereDate('from_date', '<=', CurrentDate())
                    ->whereDate('to_date', '>=', CurrentDate())
                    ->orderBy('created_at', 'desc')
                    ->where('type', 'menu')
                    ->first();

                if ($latestMenuServiceDiscount != null) {
                    $discountAmount = $latestMenuServiceDiscount->discount_price * $menuData['quantity'];
                    $totalDiscount += $discountAmount;
                    $menuData['menu_service_discount_id'] = $latestMenuServiceDiscount->id;
                    $menuData['discount_value'] = $discountAmount; // Store the calculated discount value
                } else {
                    $discountAmount = ($menuData['discount_value'] ?? 0) * $menuData['quantity'];
                }
            } else {
                $discountAmount = $menuData['discount_value'] * $menuData['quantity'];
                $totalDiscount += $discountAmount;
            }

            if ($order) {
                $order->total_quantity += $menuData['quantity'];
                $order->total_discount_price += $discountAmount; // update total discount only for this order
                $order->total += $menuData['original_price'] * $menuData['quantity'];
                $order->update($menuData);

                // $originalOrderItem = OrderItem::where('menu_id', $menuData['menu_id'])
                //     ->where('order_id', $order->id)
                //     ->first();

                $menuData['date'] = CurrentTime();
                $menuData['order_id'] = $order->id;
                $menuData['price'] = $menuData['original_price'] * $menuData['quantity'];
                // $order_items = OrderItem::create($menuData);
                // $orderItems = OrderItem::find($order_items->id);
                // $orderItems->menu = $orderItems->menu;
                // $orderItemsArray[] = $orderItems;
            } else {
                $menuData['date'] = CurrentTime();
                $menuData['total'] = $menuData['original_price'] * $menuData['quantity'];
                $menuData['total_quantity'] = $menuData['quantity'];
                $menuData['total_discount_price'] = $discountAmount; // update total discount only for this order
                $order = Order::create($menuData);
                $order->update(['order_id' => sprintf('%05d', $order->id)]);
                $menuData['order_id'] = $order->id;
                $menuData['price'] = $menuData['original_price'] * $menuData['quantity'];

                // $order_items = OrderItem::create($menuData);
                // $orderItems = OrderItem::find($order_items->id);
                // $orderItems->menu = $orderItems->menu;
                // $orderItemsArray[] = $orderItems;
            }

            $order_item = OrderItem::create($menuData);
            if ($order_item->is_foc == 1) {
                $focTotal += $order_item->price;
            }
            $order_item->menu = $order_item->menu;
            array_push($orderItemsArray, $order_item);
        }
        $order->foc_total += $focTotal;
        $order->save();
        if (isset($data['is_waiter'])) {
            if ($data['is_waiter'] == 1) {
                broadcast(new WaiterOrderConfirmNotificationRequest($entity, $order, $orderItemsArray, null, 5));
            }
        }
        broadcast(new KitchenNotificationRequest($entity, $order, $orderItemsArray, null, 7));
        $data['order'] = $order;
        $data['orderItems'] = $orderItemsArray;
        return $data;
    }
}