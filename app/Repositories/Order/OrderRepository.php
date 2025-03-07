<?php

namespace App\Repositories\Order;

use App\Models\Menu;
use App\Models\Pack;
use App\Models\Order;

use App\Models\Staff;

use App\Models\Entity;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\Department;
use App\Models\RoomSession;
use Illuminate\Http\Request;
use App\Traits\CheckMenuPack;
use App\Models\InvoiceSession;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\InvoiceModelService;
use App\Events\WaiterNotificationRequest;
use App\Http\Resources\OrderItemResource;
use App\Events\KitchenNotificationRequest;
use App\Events\OrderStatusNotificationRequest;
use App\Events\KitchenNotificationRequestByArea;
use App\Events\WaiterOrderConfirmNotificationRequest;
use App\Http\Action\SendNotification\SendNotification;
use App\Models\MenuArea;
use App\Models\MenuCategoryArea;

class OrderRepository implements OrderRepositoryInterface
{
    use SendNotification, CheckMenuPack;
    private $orderService;
    private $invoiceService;
    public function __construct(OrderService $orderService, InvoiceModelService $invoiceService)
    {
        $this->orderService = $orderService;
        $this->invoiceService = $invoiceService;
    }

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
            // dd($menu);
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
                $order = $this->orderService->updateOrderItemAmountToOrder('add', $order, $data['original_price'], $data['quantity'], $discountAmount);
                $invoice = $this->orderService->updateOrderItemAmountToInvoice('add', $invoice, $data['original_price'], $data['quantity'], $discountAmount);
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
                    $createdOrderItem->order=$createdOrderItem->order;
                    $createdOrderItem->menu=$createdOrderItem->menu;
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
                    $createdOrderItem->order=$createdOrderItem->order;
                    $createdOrderItem->menu=$createdOrderItem->menu;
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
            $sellingAreaId=$data['selling_area_id'];
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

                $menuData['invoice_id'] = $invoiceId;
                $menu = Menu::find($menuData['menu_id']);
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
                $quantityCount = (int) $menuData['quantity'];
                $defaultQuantity = 1;

                if (!isset($menuData['discount_value'])) {
                    $latestMenuServiceDiscount = $menu->menuServiceDiscounts()
                        ->whereDate('from_date', '<=', CurrentDate())
                        ->whereDate('to_date', '>=', CurrentDate())
                        ->orderBy('created_at', 'desc')
                        ->where('type', 'menu')
                        ->first();
                    $discountAmount = 0;
                    $defaultDiscountAmont=0;
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
                    $order = $this->orderService->updateOrderItemAmountToOrder('add', $order, $menuData['original_price'], $menuData['quantity'], $discountAmount);
                    $invoice = $this->orderService->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);
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
                    $invoice = $this->orderService->updateOrderItemAmountToInvoice('add', $invoice, $menuData['original_price'], $menuData['quantity'], $discountAmount);

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
                    $order_item->order=$order_item->order;
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

    public function orderItemStatusChange(array $data)
    {
        DB::beginTransaction();
        try {
            $orderItem = OrderItem::find($data['id']);
            if ($data['status'] == 'cancelled') {
                if (!checkDepartmentAndRoles('Catering', ['Staff', 'Waiter'])) {
                    ResponseMessage("Permission doesn't allow", 422);
                }
                $nonCancellableStatuses = [
                    'in progress' => "Order item can't be canceled because it is already in progress.",
                    'done' => "Order item can't be canceled because it is already done.",
                    'pos_confirmed' => "Order item can't be canceled because it is already confirmed.",
                ];
                if (isset($nonCancellableStatuses[$orderItem->status])) {
                    ResponseMessage($nonCancellableStatuses[$orderItem->status], 422);
                }
            }
            $invoice = Invoice::find($orderItem->order->invoice_id);
            //must be check entity or room
            if ($invoice->entity_id != null) {
                $entity = $invoice->entity;
            } else {
                $activeInvoiceSession = $invoice->activeInvoiceSession;
                if (!$activeInvoiceSession) {
                    ResponseMessage('Active Invioce Session not found', 419);
                }
                $entity = $activeInvoiceSession->entity;
            }
            // $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
            // $entity = $latestRoomSession->entitySession->entity;
            // $entity = Entity::find($latestRoomSession->entitySession->entity_id);
            if ($data['status'] == 'done' && $orderItem->status == 'in progress') {
                $packs = Pack::where('menu_id', $orderItem->menu_id)->where('status', 'ready')->where('expired_at', '>', CurrentTime())->orderBy('expired_at', 'asc')->take($orderItem->quantity)->get();
                if (count($packs) < $orderItem->quantity) {
                    ResponseMessage('Not enough packs to sell', 402);
                }
                foreach ($packs as $pack) {
                    if ($pack->status == 'ready') {
                        $pack->status = 'sold';
                        $pack->save();
                    }
                }
                $orderItem->completed_at = now();
                $orderItem->completed_by = UserData()->id;
            }
            //tem command 
            elseif ($data['status'] == 'in progress' && $orderItem->status == 'pos_confirmed') {
                $orderItem->progressed_at = now();
                $orderItem->progressed_by = UserData()->id;
            } elseif ($data['status'] == 'cancelled') {
                $orderItem->cancelled_at = now();
                $orderItem->cancelled_by = UserData()->id;
            } elseif ($data['status'] == 'placed' && $orderItem->status == 'done') {
                $orderItem->placed_at = now();
                $orderItem->placed_by = UserData()->id;
            } else {
                ResponseMessage("Order can't place at this moment ", 422);
            }
            $orderItem->status = $data['status'];
            $orderItem->update();
            $orderItem->menu = $orderItem->menu;
            broadcast(new OrderStatusNotificationRequest($entity, $orderItem, 5));
            DB::commit();
            ResponseMessage('Order Item status is changed successfully');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getOrderItemData(Request $request)
    {
        $areaId = $request->area_id;
        if ($request->per_page || $request->page) {
            if ($request->date) {
                $date = $request->date;
            } else {
                $date = CurrentDate();
            }
            // $startTime = $date . ' 00:00:00';
            // $endTime = $date . ' 23:59:59';
            $order_items = OrderItem::whereIn('status', ['pos_confirmed', 'in progress', 'done'])
                ->with('menu', 'order:id,order_id,invoice_id', 'order.invoice:id,entity_id', 'area', 'order.invoice.table') // change entiy to entitySession
                // ->whereBetween('date', [$startTime, $endTime])
                ->where('area_id', $areaId)
                ->orderBy('id', 'desc')
                ->paginate(config(key: 'common.list_count'));

            $order_items->getCollection()->transform(function ($orderItem) {
                $invoice = $orderItem->order->invoice ?? null;

                if ($invoice) {
                    // Unset roomSession and table from the invoice
                    // Determine entity_name based on entity_id
                    if ($invoice->entity_id !== null) {
                        $orderItem->entity_name = $invoice->table->name ?? '';
                    } else {
                        $activeInvoiceSession = $invoice->activeInvoiceSession;
                        if (!$activeInvoiceSession) {
                            ResponseMessage('Not found', 419);
                        }
                        $orderItem->entity_name = $activeInvoiceSession->entity->name;
                    }
                    // if ($invoice->entity_id !== null) {
                    //     // If entity_id is not null, use the table name
                    //     $orderItem->entity_name = $invoice->table->name ?? '';
                    // } else {
                    //     // If entity_id is null, derive from roomSessions
                    //     $roomSessions = $invoice->roomSession ?? [];
                    //     $uniqueEntities = collect($roomSessions)
                    //         ->pluck('entitySession.entity.name')
                    //         ->unique()
                    //         ->join(', '); // Join unique entity names

                    //     $orderItem->entity_name = $uniqueEntities;
                    // }
                    // unset($invoice->roomSession);
                    // unset($invoice->table);
                }

                return $orderItem;
            });

            return $order_items;
        } else {
            if ($request->date) {
                $startTime = $request->date . ' 00:00:00';
                $endTime = $request->date . ' 23:59:59';
                $orderItems = OrderItem::with('menu', 'order.invoice.latestSession.entity', 'area')
                    ->whereBetween('date', [$startTime, $endTime])->get();
            } else {
                $orderItems = OrderItem::with('menu', 'order.invoice.latestSession.entity', 'area')
                    ->whereIn('status', ['pos_confirmed', 'in progress', 'done'])
                    ->get();
            }
            return $orderItems;
        }
    }


    // for pos
    public function getOrderItemByPos()
    {
        $orderItems = OrderItem::with('area', 'menu.menuPlaces.area')
            ->orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        if ($orderItems) {
            return OrderItemResource::collection($orderItems);
        } else {
            return ResponseMessage('No order items found', 404);
        }
    }

    public function orderItemAreaConfirm(int $id, $request)
    {
        if (!UserData()) {
            ResponseMessage('Unauthenticated user', 401);
        }
        DB::beginTransaction();
        try {
            $orderItem = OrderItem::with('menu', 'order', 'order.invoice', 'area', 'order.invoice.table')
                ->find($id);
            if ($orderItem->status != 'not yet') {
                ResponseMessage('Item already confirmed', 419);
            } else if ($request->status == $orderItem->status) {
                ResponseMessage('Item status already updated', 419);
            }
            if (!$orderItem) {
                ResponseMessage('Order Item not found', 404);
            }
            $order = $orderItem->order;
            if (!$order) {
                ResponseMessage('Order not found', 419);
            }
            $invoice = $order->invoice ?? null;
            if (!$invoice) {
                return ResponseMessage('Invoice not found', 404);
            }

            $entity = $invoice->entity_id ? $invoice->table : InvoiceSession::where('is_active', 1)
                ->where('invoice_id', $invoice->id)
                ->with('entity') // Eager load entity
                ->first()
                    ?->entity; // Use null safe operator to avoid errors

            if (!$entity) {
                return ResponseMessage('Entity not found', 404);
            }

            $orderItem->update([
                'area_id' => $request->area_id,
                'status' => $request->status,
                'cancelled_at' => now(),
                'cancelled_by' => UserData()->id,
            ]);
            if ($request->status == 'rejected') {
                $orderItem->entity_name = $entity->name;
                //update order amount to invoice and order
                $order->total -= $orderItem->price;
                $order->order_sub_total -= $orderItem->sub_total_price;
                $order->total_discount_price -= $orderItem->discount_value;
                $order->save();

                $invoice->total -= $orderItem->price;
                $invoice->sub_total -= $orderItem->sub_total_price;
                $invoice->order_discount_value -= $orderItem->discount_value;
                $invoice->total_discount -= $orderItem->discount_value;
                $invoice->save();
            }
            // broadcast(new KitchenNotificationRequest($entity, $order, null, $orderItem, 7));
            broadcast(new KitchenNotificationRequestByArea($orderItem, $request->area_id));
            DB::commit();
            ResponseData($orderItem);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function orderByInvoiceId(int $invoiceId)
    {
        $order = Order::where('invoice_id', $invoiceId)->with('orderItems.menu')->get();
        ResponseData($order);
    }
    public function checkFocSupervision(Request $request)
    {
        $supervisor = Staff::whereHas('department', function ($query) {
            $query->where('name', 'Catering');
        })
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Supervisor', 'Manager']);
            })
            ->where('phone_number', $request->phone_number)->first();

        if (!$supervisor) {
            ResponseMessage('No supervisor found', 404);
        }
        if (Hash::check($request->password, $supervisor->getAuthPassword())) {
            ResponseMessage('Supervisor confirmed');
        } else {
            ResponseMessage('Supervisor authorization failed', 403);
        }
    }

    public function combineOrderItem($request)
    {
        $orderItemIds = $request->order_item_ids;
        $generateUniqueId = now()->format('YmdHis') . '_' . implode('_', $orderItemIds) . '_' . rand(1000000, 9999999);
        $orderItemQuery = OrderItem::whereIn('id', $orderItemIds)
            ->where('status', 'pos_confirmed')
            ->whereNull('group_order_id');
        $getOrderItem = $orderItemQuery->get();
        if ($getOrderItem->isNotEmpty()) {
            $orderItemQuery->update(
                [
                    'group_order_id' => $generateUniqueId,
                ]
            );
            ResponseMessage('Grouped is successfully', 200);
        }
        ResponseMessage('Grouped Order Item fail!', 200);

    }
    public function getOrderItemGroupList($request)
    {
        $groupedOrderItem = OrderItem::whereNotNull('group_order_id')
            ->join('menus', 'order_items.menu_id', 'menus.id')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->select(
                'order_items.group_order_id',
                DB::raw('GROUP_CONCAT(order_items.id SEPARATOR ", ") as order_item_ids'),
                DB::raw('GROUP_CONCAT(order_items.order_id SEPARATOR ", ") as order_ids'),
                DB::raw('GROUP_CONCAT(menus.id SEPARATOR ", ") as menu_ids'),
                DB::raw('GROUP_CONCAT(menus.name SEPARATOR ", ") as menu_names'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
            )
            ->groupBy('group_order_id')
            ->get();
        return $groupedOrderItem;
    }
}
