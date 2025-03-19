<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\MenuArea;
use App\Models\OrderItem;
use App\Models\RoomSession;
use GuzzleHttp\Psr7\Response;
use App\Models\MenuCategoryArea;
use Illuminate\Support\Facades\DB;
use App\Events\KitchenNotificationRequest;
use App\Events\KitchenNotificationRequestByArea;
use App\Events\WaiterOrderConfirmNotificationRequest;

class OrderService
{
    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            //check and remove pack is enought for menu;
            // $this->removePackForMenu($data['menu_id'], $data['quantity']);
            $price = $data['original_price'] * $data['quantity'];
            // $data['invoice'] must be unsigned integer format , not 000023
            if (!isset($data['selling_area_id']) || $data['selling_area_id'] == null) {
                ResponseMessage('Selling Area is required', 419);
            }
            $sellingAreaId = $data['selling_area_id'];
            $order = Order::where('invoice_id', $data['invoice_id'])->first();
            $invoice = Invoice::find($data['invoice_id']);
            if (!$invoice) {
                ResponseMessage('Invoice Not found', 419);
            }
            $menu = Menu::find($data['menu_id']);
            $menuCategoryArea = MenuCategoryArea::where('menu_category_id', $menu->menu_category_id)
                ->where('selling_area_id', $sellingAreaId)
                ->first();
            if (!$menuCategoryArea) {
                ResponseMessage('Menu Category Area not found', 404);
            }
            $menuArea = MenuArea::where('menu_category_area_id', $menuCategoryArea->id)
                ->where('is_default', 1)
                ->first();
            if (!$menuArea) {
                ResponseMessage('Menu Area not found', 404);
            }
            $cookingAreaId = $menuArea->cooking_area_id;

            if (!$menu) {
                ResponseMessage('Menu not found', 404);
            }
            $latestMenuServiceDiscount = $menu->menuServiceDiscounts()
                ->whereDate('from_date', '<=', CurrentDate())
                ->whereDate('to_date', '>=', CurrentDate())
                ->orderBy('created_at', 'desc')
                ->where('type', 'menu')
                ->first();
            $quantityCount = (int) $data['quantity'];
            $defaultQuantity = 1;
            $discountAmount = 0;
            $defaultDiscountAmont = 0;
            if ($latestMenuServiceDiscount) {
                $discountAmount = $latestMenuServiceDiscount->discount_price * $data['quantity'];
                $data['menu_service_discount_id'] = $latestMenuServiceDiscount->id;
                $data['discount_value'] = $latestMenuServiceDiscount->discount_price * $defaultQuantity; //discount value for one quantity
                $defaultDiscountAmont = $latestMenuServiceDiscount->discount_price * $defaultQuantity;

            }
            if ($order) {
                //add menu for existing order
                $order = $this->updateOrderItemAmountToOrder('add', $order, $data['original_price'], $data['quantity'], $discountAmount);
                $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $data['original_price'], $data['quantity'], $discountAmount);
                // $data['date'] = currentTime();
                // $data['order_id'] = $order->id;
                // $data['price'] = $data['original_price'] * $data['quantity'];
                // $data['sub_total_price'] =($data['original_price'] * $data['quantity'])-$discountAmount;
                // $order_items = OrderItem::create($data);
                $data['date'] = currentTime();
                $data['order_id'] = $order->id;
                $data['price'] = $data['original_price'] * $defaultQuantity;
                $data['sub_total_price'] = ($data['original_price'] * $defaultQuantity) - $defaultDiscountAmont;
                $orderItemData['order_id'] = $order->id;
                $orderItemData['menu_id'] = $data['menu_id'];
                $orderItemData['date'] = now();
                $orderItemData['quantity'] = $defaultQuantity;
                $orderItemData['remark'] = $data['remark'];
                $orderItemData['status'] = 'pos_confirmed'; //default
                $orderItemData['area_id'] = $cookingAreaId;
                $orderItemData['menu_service_discount_id'] = $latestMenuServiceDiscount ? $latestMenuServiceDiscount->id : null;
                $orderItemData['original_price'] = $data['original_price'];
                $orderItemData['discount_value'] = $defaultDiscountAmont;
                $orderItemData['sub_total_price'] = ($data['original_price']) - $defaultDiscountAmont; //after  
                $orderItemData['price'] = $data['original_price']; //after  

                $insertData = [];
                for ($i = 0; $i < (int) $quantityCount; $i++) {
                    // $insertData[] = $orderItemData;
                    $createdOrderItem = OrderItem::create($orderItemData);
                    $createdOrderItem->order = $createdOrderItem->order;
                    $createdOrderItem->menu = $createdOrderItem->menu;
                    $insertData[] = $createdOrderItem;
                }
                // OrderItem::insert($insertData);
                broadcast(new KitchenNotificationRequestByArea($insertData, $cookingAreaId));
                DB::commit();
                return $order;
                // $order->total_quantity += $data['quantity'];
                // $order->total_discount_price += $discountAmount;
                // $order->total += $data['original_price'] * $data['quantity'];
                // $order->order_sub_total +=($data['original_price'] * $data['quantity'])-$discountAmount;
                // $order->save();
                // $invoice->total +=$data['original_price'] * $data['quantity'];
                // $invoice->sub_total += ($data['original_price'] * $data['quantity'])-$discountAmount;
                // $invoice->order_discount_value += $discountAmount;
                // $invoice->total_discount+=$discountAmount;
            } else {
                //new order
                $data['date'] = currentTime();
                $data['total'] = $data['original_price'] * $data['quantity'];
                $data['order_sub_total'] = ($data['original_price'] * $data['quantity']) - $discountAmount;
                $data['total_quantity'] = $data['quantity'];
                $data['total_discount_price'] = $discountAmount;
                $order = Order::create($data);
                $order->update(['order_id' => sprintf('%05d', $order->id)]);
                //close for order item create default 1
                // $data['order_id'] = $order->id;
                // $data['sub_total_price'] = ($data['original_price'] * $data['quantity']) - $discountAmount; //after  
                // $data['price'] = ($data['original_price'] * $data['quantity']); //after  
                // $order_items = OrderItem::create($data);
                //end


                //update order amount to invoice   
                $invoice = $this->orderService->updateOrderItemAmountToInvoice('add', $invoice, $data['original_price'], $data['quantity'], $discountAmount);
                // change order item creat depend on quantity , like quantity=2 , create order two time, quantity=3 ,creat 3 time

                $orderItemData['order_id'] = $order->id;
                $orderItemData['menu_id'] = $data['menu_id'];
                $orderItemData['date'] = now();
                $orderItemData['quantity'] = $defaultQuantity;
                $orderItemData['remark'] = $data['remark'];
                $orderItemData['area_id'] = $cookingAreaId;
                $orderItemData['status'] = 'pos_confirmed'; //defulat
                $orderItemData['menu_service_discount_id'] = $latestMenuServiceDiscount ? $latestMenuServiceDiscount->id : null;
                $orderItemData['original_price'] = $data['original_price'];
                $orderItemData['discount_value'] = $defaultDiscountAmont;
                $orderItemData['sub_total_price'] = ($data['original_price']) - $defaultDiscountAmont; //after  
                $orderItemData['price'] = $data['original_price']; //after  
                $insertData = [];
                for ($i = 0; $i < (int) $quantityCount; $i++) {
                    // $insertData[] = $orderItemData;
                    $createdOrderItem = OrderItem::create($orderItemData);
                    $createdOrderItem->order = $createdOrderItem->order;
                    $createdOrderItem->menu = $createdOrderItem->menu;
                    $insertData[] = $createdOrderItem;
                    // dd($createdOrderItem);
                }
                // $orderItems=OrderItem::insert($insertData);
                // dd($orderItems);
                // broadcast(new KitchenNotificationRequest($entity, $order, null, $order_items, 7));
                broadcast(new KitchenNotificationRequestByArea($insertData, $cookingAreaId));
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
            $order = Order::where('invoice_id', $invoiceId)->first();
            // $sellingAreaId = $data['selling_area_id'];
            $categorySums = [];
            $totalDiscount = 0;
            $invoice = Invoice::find($data['invoice_id']);
            if ($invoice->entity_id != null) {
                $entity = $invoice->entity;
            } else {
                $activeInvoiceSession = $invoice->activeInvoiceSession;
                if (!$activeInvoiceSession) {
                    ResponseMessage('Active Entity Session not found', 419);
                }
                $entity = $activeInvoiceSession->entity;
            }

            // $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
            // $entity = Entity::find($latestRoomSession->entitySession->entity_id);

            $orderItemsArray = [];
            $focTotal = 0;
            foreach ($data['menuArray'] as $menuData) {
                $menu = Menu::find($menuData['menu_id']);
                $menuData['invoice_id'] = $invoiceId;
                if (!isset($menuData['cooking_area_id'])) {
                    ResponseMessage('Cooking Area  is required', 419);
                } elseif (isset($menuData['cooking_area_id']) && ($menuData['cooking_area_id'] == null || $menuData['cooking_area_id'] == "null")) {
                    ResponseMessage('Cooking Area  is required', 419);
                }

                if (!$menu) {
                    ResponseMessage('Menu is invalid', 419);
                }
                $menuData['area_id'] = $menuData['cooking_area_id'];
                $cookingAreaId = $menuData['cooking_area_id'];

                // $menuCategoryArea = MenuCategoryArea::where('menu_category_id', $menu->menu_category_id)
                //     ->where('selling_area_id', $sellingAreaId)
                //     ->first();
                // if (!$menuCategoryArea) {
                //     ResponseMessage('Menu Category Area not found', 404);
                // }
                // $menuArea = MenuArea::where('menu_category_area_id', $menuCategoryArea->id)
                //     ->where('is_default', 1)
                //     ->first();
                // if (!$menuArea) {
                //     ResponseMessage('Menu Area not found', 404);
                // }
                // $cookingAreaId = $menuArea->cooking_area_id;
                $quantityCount = (int) $menuData['quantity'];
                $defaultQuantity = 1;
                $defaultDiscountAmont = 0;
                if (!isset($menuData['discount_value'])) {
                    $latestMenuServiceDiscount = $menu->menuServiceDiscounts()
                        ->whereDate('from_date', '<=', CurrentDate())
                        ->whereDate('to_date', '>=', CurrentDate())
                        ->orderBy('created_at', 'desc')
                        ->where('type', 'menu')
                        ->first();
                    $discountAmount = 0;
                    $defaultDiscountAmont = 0;
                    if ($latestMenuServiceDiscount != null) {
                        $discountAmount = $latestMenuServiceDiscount->discount_price * $menuData['quantity'];
                        $totalDiscount += $discountAmount;
                        $menuData['menu_service_discount_id'] = $latestMenuServiceDiscount->id;
                        $menuData['discount_value'] = $discountAmount; // Store the calculated discount value
                        $defaultDiscountAmont = $latestMenuServiceDiscount->discount_price * $defaultQuantity;
                    }
                    // dd($defaultDiscountAmont);
                    //  else {
                    //     $discountAmount = ($menuData['discount_value'] ?? 0) * $menuData['quantity'];
                    // }
                    // dd('abc');
                } else {
                    $latestMenuServiceDiscount=null;
                    $discountAmount = $menuData['discount_value'] * $menuData['quantity'];
                    $totalDiscount += $discountAmount;
                }
                // dd($order);
                if ($order) {
                    // $order->total_quantity += $menuData['quantity'];
                    // $order->total_discount_price += $discountAmount; // update total discount only for this order
                    // $order->total += $menuData['original_price'] * $menuData['quantity'];
                    // $order->order_sub_total += $menuData['original_price'] * $menuData['quantity'];
                    // $order->update($menuData);
                    $order = $this->updateOrderItemAmountToOrder('add', $order, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    $originalOrderItem = OrderItem::where('menu_id', $menuData['menu_id'])
                        ->where('order_id', $order->id)
                        ->first();
                    $menuData['date'] = CurrentTime();
                    $menuData['order_id'] = $order->id;
                    $menuData['price'] = $menuData['original_price'];
                    $menuData['status'] = 'pos_confirmed';
                    $menuData['area_id'] = $cookingAreaId;
                    $menuData['sub_total_price'] = ($menuData['original_price']) - $defaultDiscountAmont; //after  
                    $menuData['discount_value'] = $defaultDiscountAmont;
                    // $order_items = OrderItem::create($menuData);
                    // $orderItems = OrderItem::find($order_items->id);
                    // $orderItems->menu = $orderItems->menu;
                    // $orderItemsArray[] = $orderItems;
                } else {
                    $orderData['invoice_id'] = $invoiceId;
                    $orderData['date'] = CurrentTime();
                    $orderData['total'] = $menuData['original_price'] * $menuData['quantity'];
                    $orderData['order_sub_total'] = ($menuData['original_price'] * $menuData['quantity']) - $discountAmount;
                    $orderData['total_quantity'] = $menuData['quantity'];
                    $orderData['total_discount_price'] = $discountAmount; // update total discount only for this order
                    $order = Order::create($orderData);
                    $order->update(['order_id' => sprintf('%05d', $order->id)]);
                    $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);

                    $menuData['order_id'] = $order->id;
                    $menuData['date'] = now();
                    $menuData['quantity'] = $defaultQuantity;
                    $menuData['status'] = 'pos_confirmed';
                    // $menuData['remark'] = $menuData['remark'];
                    // $menuData['original_price'] = $data['original_price'];
                    // $menuData['menu_id'] = $data['menu_id'];
                    $menuData['menu_service_discount_id'] = $latestMenuServiceDiscount ? $latestMenuServiceDiscount->id : null;
                    $menuData['discount_value'] = $defaultDiscountAmont;
                    $menuData['area_id'] = $cookingAreaId;
                    $menuData['sub_total_price'] = ($menuData['original_price']) - $defaultDiscountAmont; //after  
                    $menuData['price'] = $menuData['original_price']; //after  

                    // $menuData['order_id'] = $order->id;
                    // $menuData['price'] = $menuData['original_price'] * $menuData['quantity'];

                    // $order_items = OrderItem::create($menuData);
                    // $orderItems = OrderItem::find($order_items->id);
                    // $orderItems->menu = $orderItems->menu;
                    // $orderItemsArray[] = $orderItems;
                }
                $insertData = [];
                for ($i = 0; $i < (int) $quantityCount; $i++) {
                    // $insertData[] = $orderItemData;
                    $order_item = OrderItem::create($menuData);
                    $insertData[] = $order_item;
                    if ($order_item->is_foc == 1) {
                        $focTotal += $order_item->price;
                    }
                    $order_item->menu = $order_item->menu;
                    $order_item->order = $order_item->order;
                    array_push($orderItemsArray, $order_item);
                }
                // dd($insertData);
                // dd($menuData);
                // $order_item = OrderItem::create($menuData);
                // dd($order_item);

            }
            broadcast(new KitchenNotificationRequestByArea($orderItemsArray, $cookingAreaId));
            $order->foc_total += $focTotal;
            $order->save();
            //doesn't need to do waiter
            // if (isset($data['is_waiter'])) {
            //     if ($data['is_waiter'] == 1) {
            //         broadcast(new WaiterOrderConfirmNotificationRequest($entity, $order, $orderItemsArray, null, 5));
            //     }
            // }
            //end waiter

            //current close because ,i got an error
            // broadcast(new KitchenNotificationRequest($entity, $order, $orderItemsArray, null, 7));
            DB::commit();
            $data['order'] = $order;
            $data['orderItems'] = $orderItemsArray;
            return $data;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function createOrderOld(array $data)
    {
        // DB::beginTransaction();
        // try {
        $price = $data['original_price'] * $data['quantity'];
        // $data['invoice'] must be unsigned integer format , not 000023
        $order = Order::where('invoice_id', $data['invoice_id'])->first();
        if (!$order) {
            ResponseMessage('Order not found', 404);
        }
        $menu = Menu::find($data['menu_id']);
        if (!$menu) {
            ResponseMessage('Menu not found', 404);
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

    }
    public function createMultipleOrderOld(array $data)
    {
        $invoiceId = $data['invoice_id'];
        $order = Order::where('invoice_id', $invoiceId)->first();
        $categorySums = [];
        $totalDiscount = 0;
        $invoice = Invoice::find($data['invoice_id']);
        if (!$invoice) {
            ResponseMessage('Invoice is invalid', 419);
        }
        $invoiceSesion = $invoice->activeInvoiceSession;
        if (!$invoiceSesion) {
            ResponseMessage('Invoice Session is invalid', 419);
        }
        // $latestRoomSession = RoomSession::where('invoice_session_id', $invoiceSesion->id)->orderBy('created_at', 'desc')->first();
        $entity = Entity::find($invoiceSesion->entity_id);

        $orderItemsArray = [];
        $focTotal = 0;
        foreach ($data['menuArray'] as $menuData) {
            $menu = Menu::where('is_active', 1)->find($menuData['menu_id']);

            if (!isset($menuData['cooking_area_id'])) {
                ResponseMessage('Cooking Area  is required', 419);
            } elseif (isset($menuData['cooking_area_id']) && ($menuData['cooking_area_id'] == null || $menuData['cooking_area_id'] == "null")) {
                ResponseMessage('Cooking Area  is required', 419);
            }

            if (!$menu) {
                ResponseMessage('Menu is invalid', 419);
            }
            $menuData['area_id'] = $menuData['cooking_area_id'];
            // $sellingAreaId = $data['selling_area_id'];
            // $sellingAreaId=7;//
            // $menuArea=MenuArea::join('menu_category_areas','menu_areas.menu_category_area_id','menu_category_areas.id')
            // ->where('is_default',1)
            // ->where('menu_category_areas.selling_area_id',$sellingAreaId)
            // ->where('menu_category_areas.menu_category_id',$menu->menu_category_id)
            // ->first();
            // $menu->area_id=$menuArea->cooking_area_id;
            if (!isset($menuData['area_id']) || $menuData['area_id'] == null) {
                ResponseMessage('Area is required', 419);
            }
            $menuData['invoice_id'] = $invoiceId;
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
            dd($order_item);
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

    public function updateOrderItemAmountToOrder($action, $orderModel, $originalPrice, $quantity, $discountAmount)
    {
        if ($action == 'add') {
            $orderModel->total_quantity += $quantity;
            $orderModel->total_discount_price += $discountAmount;
            $orderModel->total += $originalPrice * $quantity;
            $orderModel->order_sub_total += ($originalPrice * $quantity) - $discountAmount;
        } else if ($action == 'subtract') {
            $orderModel->total_quantity -= $quantity;
            $orderModel->total_discount_price -= $discountAmount;
            $orderModel->total -= $originalPrice * $quantity;
            $orderModel->order_sub_total -= ($originalPrice * $quantity) - $discountAmount;
        } else {
            ResponseMessage('Action is invalid', 419);
        }

        $orderModel->save();
        return $orderModel;
    }

    public function updateOrderItemAmountToInvoice($action, $invoiceModel, $originalPrice, $quantity, $discountAmount)
    {


        if ($action == 'add') {
            $invoiceModel->total += $originalPrice * $quantity;
            $invoiceModel->sub_total += ($originalPrice * $quantity) - $discountAmount;
            $invoiceModel->order_discount_value += $discountAmount;
            $invoiceModel->total_discount += $discountAmount;
        } else if ($action == 'subtract') {
            $invoiceModel->total -= $originalPrice * $quantity;
            $invoiceModel->sub_total -= ($originalPrice * $quantity) - $discountAmount;
            $invoiceModel->order_discount_value -= $discountAmount;
            $invoiceModel->total_discount -= $discountAmount;
        } else {
            ResponseMessage('Action is invalid', 419);
        }
        $invoiceModel->save();
        return $invoiceModel;

    }

}