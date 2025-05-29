<?php

namespace App\Repositories\Order;

use App\Models\Menu;
use App\Models\Pack;
use App\Models\Order;

use App\Models\Staff;

use App\Models\Entity;
use App\Models\Invoice;
use App\Models\MenuArea;
use App\Models\OrderItem;
use App\Models\Department;
use App\Models\RoomSession;
use Illuminate\Http\Request;
use App\Traits\CheckMenuPack;
use App\Models\InvoiceSession;
use App\Services\OrderService;
use App\Models\MenuCategoryArea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\InvoiceModelService;
use App\Events\WaiterNotificationRequest;
use App\Http\Resources\OrderItemResource;
use App\Events\KitchenNotificationRequest;
use App\Events\OrderStatusNotificationRequest;
use App\Events\KitchenNotificationRequestByArea;
use App\Events\OrderItemCombineNotificationRequest;
use App\Events\WaiterOrderConfirmNotificationRequest;
use App\Http\Action\SendNotification\SendNotification;

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
        return $this->orderService->createOrder($data);
    }

    public function createMultipleOrder(array $data)
    {
        return $this->orderService->createMultipleOrder($data);
    }

    public function orderItemStatusChange(array $data)
    {
        DB::beginTransaction();
        try {
            if ($data['status'] == 'placed') {
                if (!checkDepartmentAndRoles('Catering', ['Staff', 'Waiter'])) {
                    ResponseMessage("Permission doesn't allow", 422);
                }
                $orderItem = OrderItem::where('id', $data['id'])->first();
                if (!$orderItem) {
                    ResponseMessage("Order Item Not Found", 422);
                }
                if ($orderItem->status !== 'done') {
                    ResponseMessage("Order can't place at this moment ", 422);
                }
                $orderItem->status = $data['status'];
                $orderItem->placed_at = now();
                $orderItem->placed_by = UserData()->id;
                $orderItem->update();
                $orderItem->menu = $orderItem->menu;
                // broadcast(new OrderStatusNotificationRequest($entity, $orderItem, 5));
            } else {
                $orderItems = OrderItem::where('group_order_id', $data['group_order_id'])->get();
                if (!$orderItems) {
                    ResponseMessage('Order Item not found', 404);
                }
                foreach ($orderItems as $orderItem) {
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
                }
                foreach ($orderItems as $orderItem) {
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
                }
            }

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
        DB::beginTransaction();
        try {
            $orderItemIds = $request->order_item_ids;
            $generateUniqueId = now()->format('YmdHis') . '_' . implode('_', $orderItemIds) . '_' . rand(1000000, 9999999);

            $orderItemQuery = OrderItem::whereIn('id', $orderItemIds)
                ->where('status', 'pos_confirmed')
                ->whereNull('group_order_id');
            $getOrderItem = $orderItemQuery->get();
            $cookingAreaIds = $getOrderItem->pluck('area_id')->unique();
            if ($getOrderItem->isNotEmpty()) {
                $orderItemQuery->update(
                    [
                        'group_order_id' => $generateUniqueId,
                    ]
                );
                foreach ($cookingAreaIds as $cookingAreaId) {
                    broadcast(new OrderItemCombineNotificationRequest($cookingAreaId));
                }
                DB::commit();
                ResponseMessage('Grouped is successfully', 200);
            } else {
                ResponseMessage('Order Item not found or already grouped!', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
    public function getOrderItemGroupList($request)
    {
        $groupedOrderItem = OrderItem::whereNotNull('group_order_id')
            ->join('menus', 'order_items.menu_id', 'menus.id')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->join('areas', 'order_items.area_id', 'areas.id')
            ->join('invoices', 'orders.invoice_id', 'invoices.id')
            ->join('entities', 'invoices.entity_id', 'entities.id')
            ->select(
                'menus.id as menu_id',
                'menus.name as menu_name',
                DB::raw('MIN(order_items.date) as date'),
                DB::raw('GROUP_CONCAT(DISTINCT order_items.order_id) as order_ids'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                // DB::raw('GROUP_CONCAT(DISTINCT areas.name) as area_name'),
                DB::raw('GROUP_CONCAT(DISTINCT order_items.status) as status'),
                DB::raw('GROUP_CONCAT(DISTINCT entities.name) as room_name'),
                DB::raw('GROUP_CONCAT(DISTINCT order_items.group_order_id) as group_order_id'),
                DB::raw('JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "order_item_id", order_items.id,
                        "order_id", order_items.order_id,
                        "date", order_items.date,
                        "menu_id", order_items.menu_id,
                        "menu_name", menus.name,
                        "quantity", order_items.quantity,
                        "area_id", order_items.area_id,
                        "area_name", areas.name,
                        "room_id", entities.id,
                        "room_name", entities.name,
                        "status", order_items.status,
                        "remark", IFNULL(order_items.remark, ""),
                        "group_order_id", order_items.group_order_id
                    )
                ) as order_items_details')
            )->groupBy('menus.id', 'menus.name', 'group_order_id');
        if ($request->has('area_id') && $request->area_id) {
            $groupedOrderItem->where('order_items.area_id', $request->area_id);
        }

        $groupedOrderItem = $groupedOrderItem->paginate(config('common.list_count'));
        $groupedOrderItem->getCollection()->transform(function ($item) {
            $item->order_items_details = json_decode($item->order_items_details, true);
            return $item;
        });
        return ResponseData($groupedOrderItem, 200, true, 'Order reterived successfully.');
    }

    public function getOrderItemsGroupByMenu($request)
    {
        $groupedOrderItem = OrderItem::join('menus', 'order_items.menu_id', 'menus.id')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->join('areas', 'order_items.area_id', 'areas.id')
            ->join('invoices', 'orders.invoice_id', 'invoices.id')
            ->join('entities', 'invoices.entity_id', 'entities.id')

            ->select(
                'menus.id as menu_id',
                'menus.name as menu_name',
                DB::raw('MIN(order_items.date) as date'),
                DB::raw('GROUP_CONCAT(DISTINCT order_items.order_id ORDER BY order_items.order_id) as order_ids'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                // DB::raw('GROUP_CONCAT(DISTINCT areas.name) as area_name'),
                // DB::raw('GROUP_CONCAT(order_items.area_id) as areas_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT entities.name ORDER BY entities.name) as room_name'),
                DB::raw('JSON_ARRAYAGG( JSON_OBJECT(
                "order_item_id", order_items.id,
                "order_id", order_items.order_id,
                "date", order_items.date,
                "menu_id", order_items.menu_id,
                "menu_name", menus.name,
                "quantity", order_items.quantity,
                "area_id", order_items.area_id,
                "area_name", areas.name,
                "room_id", entities.id,
                "room_name", entities.name,
                "status", order_items.status,
                "remark", IFNULL(order_items.remark, "")
            )) as order_items_details')
            )
            ->whereNull('order_items.group_order_id')
            ->groupBy('menus.id', 'menus.name')
            ->paginate(config('common.list_count'));
        $groupedOrderItem->getCollection()->transform(function ($item) {
            $item->order_items_details = json_decode($item->order_items_details, true);
            return $item;
        });
        return ResponseData($groupedOrderItem, 200, true, 'Order retrieved successfully.');
    }
}
