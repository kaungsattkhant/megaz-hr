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
use App\Models\MenuStepItem;
use GuzzleHttp\Psr7\Response;
use App\Models\InventoryLedger;
use App\Models\InvoiceAccessory;
use App\Models\MenuCategoryArea;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Events\OrderNotificationByArea;
use App\Events\KitchenNotificationRequest;
use App\Http\Action\Inventory\StoreInventory;
use App\Events\KitchenNotificationRequestByArea;
use App\Events\WaiterOrderConfirmNotificationRequest;
use App\Models\Inventory;
use App\Models\OrderItemExtra;
use App\Models\SellingExtra;

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
            $totalExtraPrice = 0;
            $sellingExtraIds = null;
            if (isset($data['selling_extra_id']) && !empty($data['selling_extra_id'])) {
                $sellingExtraIds = $data['selling_extra_id'];
                foreach ($data['selling_extra_id'] as $sellingExtraId) {
                    $sellingExtra = SellingExtra::find($sellingExtraId);
                    $totalExtraPrice += $sellingExtra->price;
                }
            }
            if ($order) {
                //add menu for existing order
                $order = $this->updateOrderItemAmountToOrder('add', $order, ($data['original_price'] + $totalExtraPrice), $data['quantity'], $discountAmount);
                $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, ($data['original_price'] + $totalExtraPrice), $data['quantity'], $discountAmount);
                // $data['date'] = currentTime();
                // $data['order_id'] = $order->id;
                // $data['price'] = $data['original_price'] * $data['quantity'];
                // $data['sub_total_price'] =($data['original_price'] * $data['quantity'])-$discountAmount;
                // $order_items = OrderItem::create($data);
                $data['date'] = currentTime();
                $data['order_id'] = $order->id;
                $data['price'] = $data['original_price'] * $defaultQuantity;
                $data['sub_total_price'] = (($data['original_price'] + $totalExtraPrice) * $defaultQuantity) - $defaultDiscountAmont;
                $data['total_extra_price'] = $totalExtraPrice;
                $orderItemData['order_id'] = $order->id;
                $orderItemData['menu_id'] = $data['menu_id'];
                $orderItemData['date'] = now();
                $orderItemData['quantity'] = $defaultQuantity;
                $orderItemData['remark_id'] = $data['remark_id'];
                $orderItemData['status'] = 'pos_confirmed'; //default
                $orderItemData['area_id'] = $cookingAreaId;
                $orderItemData['menu_service_discount_id'] = $latestMenuServiceDiscount ? $latestMenuServiceDiscount->id : null;
                $orderItemData['original_price'] = $data['original_price'];
                $orderItemData['discount_value'] = $defaultDiscountAmont;
                $orderItemData['sub_total_price'] = ($data['original_price']) - $defaultDiscountAmont; //after  
                $orderItemData['price'] = $data['original_price']; //after  

                $insertData = [];
                $this->checkInventoryEnough($data['menu_id'], $quantityCount);
                for ($i = 0; $i < (int) $quantityCount; $i++) {
                    // $insertData[] = $orderItemData;
                    $createdOrderItem = OrderItem::create($orderItemData);
                    if (isset($data['selling_extra_id']) && !empty($data['selling_extra_id'])) {
                        $this->createOrderItemExtra($createdOrderItem, $data['selling_extra_id']);
                    }
                    $createdOrderItem->order = $createdOrderItem->order;
                    $createdOrderItem->menu = $createdOrderItem->menu;
                    $insertData[] = $createdOrderItem;
                    // $this->actionInventoryItem($createdOrderItem, 'order_item', 'out', $sellingExtraIds);
                }
                // OrderItem::insert($insertData);
                // broadcast(new KitchenNotificationRequestByArea($insertData, $cookingAreaId));
                broadcast(new OrderNotificationByArea($cookingAreaId)); //send notifcation to checker list
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
                //new orderf
                $data['date'] = currentTime();
                $data['total'] = ($data['original_price'] + $totalExtraPrice) * $data['quantity'];
                $data['order_sub_total'] = (($data['original_price'] + $totalExtraPrice) * $data['quantity']) - $discountAmount;
                $data['total_extra_price'] = $totalExtraPrice * $data['quantity'];
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
                $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, ($data['original_price'] + $totalExtraPrice), $data['quantity'], $discountAmount);
                // change order item creat depend on quantity , like quantity=2 , create order two time, quantity=3 ,creat 3 time
                $orderItemData['order_id'] = $order->id;
                $orderItemData['menu_id'] = $data['menu_id'];
                $orderItemData['date'] = now();
                $orderItemData['quantity'] = $defaultQuantity;
                $orderItemData['remark_id'] = $data['remark_id'];
                $orderItemData['area_id'] = $cookingAreaId;
                $orderItemData['status'] = 'pos_confirmed'; //defulat
                $orderItemData['menu_service_discount_id'] = $latestMenuServiceDiscount ? $latestMenuServiceDiscount->id : null;
                $orderItemData['original_price'] = $data['original_price'];
                $orderItemData['discount_value'] = $defaultDiscountAmont;
                $orderItemData['sub_total_price'] = ($data['original_price']) - $defaultDiscountAmont; //after  
                $orderItemData['price'] = $data['original_price']; //after  
                $insertData = [];
                //check inventory
                $this->checkInventoryEnough($data['menu_id'], $quantityCount);
                if (isset($data['selling_extra_id']) && !empty($data['selling_extra_id'])) {
                    // $this->createOrderItemExtra($createdOrderItem, $sellingExtraIds);
                    $this->checkSellingExtraInventoryIsEnough($sellingExtraIds);
                }
                //end check inventory
                for ($i = 0; $i < (int) $quantityCount; $i++) {
                    // $insertData[] = $orderItemData;
                    $createdOrderItem = OrderItem::create($orderItemData);
                    if (isset($data['selling_extra_id']) && !empty($data['selling_extra_id'])) {
                        $this->createOrderItemExtra($createdOrderItem, $sellingExtraIds);
                    }
                    $createdOrderItem->order = $createdOrderItem->order;
                    $createdOrderItem->menu = $createdOrderItem->menu;
                    $insertData[] = $createdOrderItem;
                    $extraIds = [];
                    if (isset($data['selling_extra_id']) && !empty($data['selling_extra_id'])) {
                        $extraIds = $data['selling_extra_id'];
                    }
                    // $this->actionInventoryItem($createdOrderItem, 'order_item', 'out', $extraIds);
                    // dd($createdOrderItem);

                }
                // $orderItems=OrderItem::insert($insertData);
                // dd($orderItems);
                // broadcast(new KitchenNotificationRequest($entity, $order, null, $order_items, 7));

                // broadcast(new KitchenNotificationRequestByArea($insertData, $cookingAreaId));
                broadcast(new OrderNotificationByArea($cookingAreaId)); //send notifcation to checker list
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
            $order = Order::where('invoice_id', operator: $invoiceId)->first();
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
            if ($invoice->invoice_type == 'package') {
                $filteredMenu = array_filter($data['menuArray'], function ($menu) {
                    return isset($menu['is_package']) && in_array($menu['is_package'], [0, 1]);
                });
            } else {
                $filteredMenu = $data['menuArray'];
            }
            $cancelledMenu = array_filter($data['menuArray'], function ($menu) {
                return isset($menu['is_package']) && in_array($menu['is_package'], [-1]);
            });
            $detaultQuantity = 1;
            foreach ($filteredMenu as $menuData) {
                $this->checkInventoryEnough($menuData['menu_id'], $menuData['quantity']);
                // if($invoice->invoice_type=='package' && $menuData['is_package']=-1){}
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
                // dd($menuData);
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
                    $latestMenuServiceDiscount = null;
                    // dd($invoice->invoice_type);
                    if ($invoice->invoice_type == 'package') {
                        $discountAmount = 0;
                    } else {
                        $discountAmount = $menuData['discount_value'] * $menuData['quantity'];
                        $totalDiscount += $discountAmount;
                    }
                }
                if ($order) {
                    // $order->total_quantity += $menuData['quantity'];
                    // $order->total_discount_price += $discountAmount; // update total discount only for this order
                    // $order->total += $menuData['original_price'] * $menuData['quantity'];
                    // $order->order_sub_total += $menuData['original_price'] * $menuData['quantity'];
                    // $order->update($menuData);
                    // if ($invoice->type != 'package') {
                    if ($invoice->invoice_type == 'package' && !$menuData['is_package']) {
                        $order = $this->updateOrderItemAmountToOrder('add', $order, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    }
                    if (($invoice->invoice_type == 'session' || $invoice->invoice_type == 'endless_time')) {
                        $order = $this->updateOrderItemAmountToOrder('add', $order, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    }
                    if ($invoice->invoice_type == 'package' && !$menuData['is_package']) {
                        $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    }
                    if ($invoice->invoice_type == 'session' || $invoice->invoice_type == 'endless_time') {
                        $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    }
                    // }
                    $originalOrderItem = OrderItem::where('menu_id', $menuData['menu_id'])
                        ->where('order_id', $order->id)
                        ->first();
                    $menuData['date'] = CurrentTime();
                    $menuData['order_id'] = $order->id;
                    $menuData['quantity'] = $defaultQuantity;
                    // $menuData['price'] = $menuData['original_price'];
                    $menuData['status'] = 'pos_confirmed';
                    $menuData['area_id'] = $cookingAreaId;
                    $menuData['sub_total_price'] = (isset($menuData['is_package']) && $menuData['is_package'])
                        ? 0
                        : ($menuData['original_price']) - $defaultDiscountAmont; //after  
                    $menuData['price'] = (isset($menuData['is_package']) && $menuData['is_package'])
                        ? 0
                        : $menuData['original_price']; //after
                    // $menuData['sub_total_price'] = ($menuData['original_price']) - $defaultDiscountAmont; //after  
                    $menuData['discount_value'] = $defaultDiscountAmont;
                    // $order_items = OrderItem::create($menuData);
                    // $orderItems = OrderItem::find($order_items->id);
                    // $orderItems->menu = $orderItems->menu;
                    // $orderItemsArray[] = $orderItems;
                } else {
                    // dd($menuData);
                    $orderData['invoice_id'] = $invoiceId;
                    $orderData['date'] = CurrentTime();
                    $orderData['total'] = (isset($menuData['is_package']) && $menuData['is_package'])
                        ? 0
                        : $menuData['original_price'] * $menuData['quantity'];
                    $orderData['order_sub_total'] = (isset($menuData['is_package']) && $menuData['is_package'])
                        ? 0
                        : ($menuData['original_price'] * $menuData['quantity']) - $discountAmount;
                    $orderData['total_quantity'] = $menuData['quantity'];
                    $orderData['total_discount_price'] = $discountAmount; // update total discount only for this order

                    $order = Order::create($orderData);
                    $order->update(['order_id' => sprintf('%05d', $order->id)]);
                    if ($invoice->invoice_type == 'package' && !$menuData['is_package']) {
                        $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    }
                    if ($invoice->invoice_type == 'session' || $invoice->invoice_type == 'endless_time') {
                        $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    }
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
                    $menuData['sub_total_price'] = (isset($menuData['is_package']) && $menuData['is_package'])
                        ? 0
                        : ($menuData['original_price']) - $defaultDiscountAmont; //after  
                    $menuData['price'] = (isset($menuData['is_package']) && $menuData['is_package'])
                        ? 0
                        : $menuData['original_price']; //after  

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
                    $menuData['quantity'] = $defaultQuantity;
                    $order_item = OrderItem::create($menuData);
                    $insertData[] = $order_item;
                    if ($order_item->is_foc == 1) {
                        $focTotal += $order_item->price;
                    }
                    $order_item->menu = $order_item->menu;
                    $order_item->order = $order_item->order;
                    array_push($orderItemsArray, $order_item);
                }


            }
            if (count($cancelledMenu) > 0) {
                foreach ($cancelledMenu as $cancelData) {
                    $invoice = $this->updateOrderItemAmountToInvoice('subtract', $invoice, $cancelData['original_price'], $cancelData['quantity'], $discount = 0);
                }
            }
            // temp command for checklist
            // broadcast(new OrderNotificationByArea($cookingAreaId)); //send notifcation to checker list

            // broadcast(new KitchenNotificationRequestByArea($orderItemsArray, $cookingAreaId));
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

    public function createOrderItemExtra($orderItem, $sellingExtraIds)
    {
        $totalExtraPrice = 0;
        foreach ($sellingExtraIds as $sellingExtraId) {
            $sellingExtra = SellingExtra::find($sellingExtraId);
            $orderItemExtra = OrderItemExtra::create([
                'order_item_id' => $orderItem->id,
                'selling_extra_id' => $sellingExtraId,
            ]);
            $totalExtraPrice += $sellingExtra->price;
        }
        $orderItem->sub_total_price = $orderItem->sub_total_price + $totalExtraPrice;
        $orderItem->save();
        // $this->addOrderExtraPriceToOrder($orderItem->order, $totalExtraPrice);
    }

    public function addOrderExtraPriceToOrder($order, $totalExtraPrice)
    {

    }

    public function checkInventoryEnough($menuId, $quantity)
    {
        // $inventoryId = UserData()->department->inventory->inventory_id;
        $inventory = Inventory::where('name', 'Kitchen Inventory')->first();
        if (!$inventory) {
            ResponseMessage('Kitchen Inv not found', 404);
        }
        $inventoryId = $inventory->id;
        $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
            ->whereHas('menuStep', function ($q) use ($menuId) {
                $q->where('menu_id', $menuId)
                    ->where('type', 'ready_to_sale');
            })
            ->select('items.uom_id', 'items.name', DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'), 'menu_step_items.item_id')
            ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
            ->get();
        foreach ($menuStepItemByMenu as $item) {
            $itemInventory = InventoryLedgerItem::where('item_id', $item->item_id)
                ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->first();
            $stockInInventory = $itemInventory->in_stock_quantity ?? 0;
            $menuCostQuantity = $item->total_quantity * $quantity;
            if ((float) $stockInInventory < $menuCostQuantity) {
                return ResponseMessage("Stock is not enough for item ,{$item->name}", 422);
            }
        }

    }

    public function checkSellingExtraInventoryIsEnough($sellingExtraIds)
    {
        $inventory = Inventory::where('name', 'Kitchen Inventory')->first();
        if (!$inventory) {
            ResponseMessage('Kitchen Inv not found', 404);
        }
        $inventoryId = $inventory->id;
        foreach ($sellingExtraIds as $extraId) {
            $sellingExtra = SellingExtra::with('item')
                ->find($extraId);
            if ($sellingExtra->uom_id == $sellingExtra->item->base_uom_id) {
                $quantity = $sellingExtra->quantity * $sellingExtra->item->uom_conversion;
            }
            if ($sellingExtra->uom_id == $sellingExtra->item->uom_id) {
                $quantity = $sellingExtra->quantity;
            }
            $itemInventory = InventoryLedgerItem::where('item_id', $sellingExtra->item_id)
                ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->first();
            $stockInInventory = $itemInventory->in_stock_quantity ?? 0;
            if ((float) $stockInInventory < $quantity) {
                return ResponseMessage("Stock is not enough for extra Item ,{$sellingExtra->item->name}", 422);
            }
        }

    }

    public function actionInventoryItem($orderItem, $morphMapName, $action, $sellingExtraIds)
    {
        $inventoryId = UserData()->department->inventory->inventory_id;
        // $inventoryId=6; //fix kitchen
        $menuId = $orderItem->menu_id;
        $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
            ->whereHas('menuStep', function ($q) use ($menuId) {
                $q->where('menu_id', $menuId)
                    ->where('type', 'ready_to_sale');
            })
            ->select('items.uom_id', 'items.name', DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'), 'menu_step_items.item_id')
            ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
            ->get();

        //out extra item from inventory

        foreach ($sellingExtraIds as $extraId) {
            $sellingExtra = SellingExtra::with('item')
                ->find($extraId);
            if ($sellingExtra->uom_id == $sellingExtra->item->base_uom_id) {
                $quantity = $sellingExtra->quantity * $sellingExtra->item->uom_conversion;
            }
            if ($sellingExtra->uom_id == $sellingExtra->item->uom_id) {
                $quantity = $sellingExtra->quantity;
            }
            // $inventoryItems = InventoryLedgerItem::where('item_id', $sellingExtra->item_id)
            //     ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            //     ->where('inventory_ledgers.inventory_id', $inventoryId)
            //     ->select('inventory_ledger_items.quantity', 'inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
            //     ->groupBy('inventory_ledgers.batch_no')
            //     ->orderBy('inventory_ledgers.created_at', 'asc')
            //     ->get();
            $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id', $sellingExtra->item_id)
                ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->select(
                    'inventory_ledger_items.item_id',
                    'inventory_ledgers.batch_no'
                )
                ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                ->orderBy('inventory_ledgers.created_at', 'asc')
                ->get();
            $remainingQuantity = $quantity;
            foreach ($inventoryItems as $inventory_item) {
                if ($remainingQuantity <= 0) {
                    break;
                }

                $availableQty = (float) $inventory_item->in_stock_quantity;

                if ($availableQty <= 0) {
                    continue;
                }

                // Determine how much to take from this batch
                $quantityToTake = min($remainingQuantity, $availableQty);

                $inventoryLedger = InventoryLedger::create([
                    'batch_no' => $inventory_item->batch_no,
                    'date' => now(),
                    'ledgerable_id' => $orderItem->id,
                    'ledgerable_type' => 'selling_extra',
                    'inventory_id' => $inventoryId,
                    'action' => $action,//out
                ]);
                // $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($orderItem, $morphMapName, $action);
                $inventoryLedger->inventory_ledger_items()->create([
                    'item_id' => $sellingExtra->item_id,
                    'quantity' => $quantityToTake,
                    'inventory_ledger_id' => $inventoryLedger->id,
                ]);
                $remainingQuantity -= $quantityToTake;
            }
        }

        //end_extra

        foreach ($menuStepItemByMenu as $item) {

            //Let
            // $inventoryItems = InventoryLedgerItem::where('item_id', $item->item_id)
            //     ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            //     ->where('inventory_ledgers.inventory_id', $inventoryId)
            //     ->select('inventory_ledger_items.quantity', 'inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
            //     ->groupBy('inventory_ledgers.batch_no')
            //     ->orderBy('inventory_ledgers.created_at', 'asc')
            //     ->get();
            $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id', $item->item_id)
                ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->select(
                    'inventory_ledger_items.item_id',
                    'inventory_ledgers.batch_no'
                )
                ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                ->orderBy('inventory_ledgers.created_at', 'asc')
                ->get();
            $totalOutQuantity = $item->total_quantity; // e.g. 15000
            $remainingQuantity = $totalOutQuantity;
            $array = [];
            foreach ($inventoryItems as $inventory_item) {
                if ($remainingQuantity <= 0) {
                    break;
                }

                $availableQty = (float) $inventory_item->in_stock_quantity;

                // Determine how much to take from this batch
                if ($availableQty <= 0) {
                    continue;
                }

                $quantityToTake = min($remainingQuantity, $availableQty);

                $inventoryLedger = InventoryLedger::create([
                    'batch_no' => $inventory_item->batch_no,
                    'date' => now(),
                    'ledgerable_id' => $orderItem->id,
                    'ledgerable_type' => 'order_item',
                    'inventory_id' => $inventoryId,
                    'action' => $action,//out
                ]);
                // $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($orderItem, $morphMapName, $action);
                $inventoryLedger->inventory_ledger_items()->create([
                    'item_id' => $item->item_id,
                    'quantity' => $quantityToTake,
                    'inventory_ledger_id' => $inventoryLedger->id,
                ]);
                $remainingQuantity -= $quantityToTake;
                $array[] = $quantityToTake;
            }
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
            $orderModel->total_quantity = $orderModel->total_quantity + $quantity;
            $orderModel->total_discount_price = $orderModel->total_discount_price + $discountAmount;
            $orderModel->total = $orderModel->total + $originalPrice * $quantity;
            $orderModel->order_sub_total = $orderModel->order_sub_total + (($originalPrice * $quantity) - $discountAmount);
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

    public function updateServiceAmountToInvoice($invoice, $serviceValue)
    {
        $invoice->total += $serviceValue;
        $invoice->sub_total += $serviceValue;
        $invoice->total_service_value += $serviceValue;
        $invoice->save();
        return $invoice;
    }

    public function createAccessorty($invoice, $accessories)
    {
        $invoiceId = $invoice->id;

        if ($invoice->invoice_type == 'package') {
            $filterAccessory = array_filter($accessories, function ($accessory) {
                return isset($accessory['is_package']) && in_array($accessory['is_package'], [0, 1]);
            });
        } else {
            $filterAccessory = $accessories;
        }

        $cancelledAccessory = array_filter($accessories, function ($accessory) {
            return isset($accessory['is_package']) && in_array($accessory['is_package'], [-1]);
        });
        foreach ($filterAccessory as $accessory) {
            $invoiceAccessory = InvoiceAccessory::create([
                'quantity' => $accessory['quantity'],
                'accessory_id' => $accessory['accessory_id'],
                'invoice_id' => $invoiceId,
                'is_package' => $accessory['is_package'],
                'accessory_price' => $accessory['is_package'] ? 0 : $accessory['unit_price'],
            ]);
            if (!$accessory['is_package']) {
                $invoice = $this->updateOrderItemAmountToInvoice('add', $invoice, $accessory['unit_price'], $accessory['quantity'], $discount = 0);
            }
        }
        if (count($cancelledAccessory) > 0) {
            foreach ($cancelledAccessory as $cancelData) {
                $invoice = $this->updateOrderItemAmountToInvoice('subtract', $invoice, $cancelData['unit_price'], $cancelData['quantity'], $discount = 0);
            }
        }
        return true;
    }
}