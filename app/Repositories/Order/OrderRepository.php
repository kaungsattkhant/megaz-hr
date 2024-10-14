<?php

namespace App\Repositories\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Http\Action\SendNotification\SendNotification;

use App\Events\KitchenNotificationRequest;
use App\Events\OrderStatusNotificationRequest;
use App\Events\WaiterNotificationRequest;
use App\Events\WaiterOrderConfirmNotificationRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\Pack;
use App\Models\RoomSession;
use App\Models\Staff;
use App\Models\Department;

class OrderRepository implements OrderRepositoryInterface
{
    use SendNotification;
    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $price = $data['original_price'] * $data['quantity'];
            $order = Order::where('invoice_id', $data['invoice_id'])->get()->first();
            $menu = Menu::find($data['menu_id']);
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
            $categorySums = [];
            $totalDiscount = 0;

            $invoice = Invoice::find($data['invoice_id']);
            $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
            $entity = Entity::find($latestRoomSession->entitySession->entity_id);

            $orderItemsArray = [];
            $focTotal = 0;
            foreach ($data['menuArray'] as $menuData) {

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

                    $originalOrderItem = OrderItem::where('menu_id', $menuData['menu_id'])
                        ->where('order_id', $order->id)
                        ->first();

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
                if($order_item->is_foc == 1){
                    $focTotal += $order_item->price;
                }
                $order_item->menu = $order_item->menu;
                array_push($orderItemsArray, $order_item);
            }

            $order->foc_total += $focTotal;
            $order->save();
            if(isset($data['is_waiter']))
            {
                if($data['is_waiter']==1)
                {
                    broadcast(new WaiterOrderConfirmNotificationRequest($entity, $order, $orderItemsArray, null, 5));
                }
            }
            broadcast(new KitchenNotificationRequest($entity, $order, $orderItemsArray, null, 7));
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
            $users = UserData();
            $orderItem = OrderItem::find($data['id']);
            if ($orderItem->status == 'in_progress') {
                ResponseMessage("Order item can't be canceled because it is already in progress.", 422);
            } else if ($orderItem->status == 'in progress') {
                ResponseMessage("Order item can't be canceled because it is already in progress.", 422);
            } elseif ($orderItem->status == 'done') {
                ResponseMessage("Order item can't be canceled because it is already done.", 422);
            } elseif ($orderItem->status == 'confirmed') {
                ResponseMessage("Order item can't be canceled because it is already confirmed.", 422);
            }
            $invoice = Invoice::find($orderItem->order->invoice_id);
            $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();

            $entity = Entity::find($latestRoomSession->entitySession->entity_id);
            if ($data['status'] == 'done') {
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
        if ($request->per_page || $request->page) {
            if ($request->date) {
                $date = $request->date;
            } else {
                $date = CurrentDate();
            }
            $startTime = $date . ' 00:00:00';
            $endTime = $date . ' 23:59:59';

            $order_items = OrderItem::where('status', 'pos_confirmed')->with('menu', 'order.invoice.latestSession.entity', 'area')
                ->whereBetween('date', [$startTime, $endTime])
                ->paginate(config('common.list_count'));

            return $order_items;
        } else {
            if ($request->date) {
                $startTime = $request->date . ' 00:00:00';
                $endTime = $request->date . ' 23:59:59';
                $orderItems = OrderItem::where('status', 'pos_confirmed')->with('menu', 'order.invoice.latestSession.entity', 'area')->whereBetween('date', [$startTime, $endTime])->get();
            } else {
                $orderItems = OrderItem::where('status', 'pos_confirmed')->with('menu', 'order.invoice.latestSession.entity', 'area')->get();
            }

            return $orderItems;
        }
    }


    // for pos
    public function getOrderItemByPos()
    {
        $orderItems = OrderItem::with('area', 'menu.areas')->orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        ResponseData($orderItems);
    }

    public function orderItemAreaConfirm(int $id, Request $request)
    {
        DB::beginTransaction();
        try {

            $orderItem = OrderItem::find($id);
            $orderItem->update([
                'area_id' => $request->area_id,
                'status' => $request->status,
            ]);

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
        $order = Order::where('invoice_id',$invoiceId)->with('orderItems.menu')->get();
        ResponseData($order);

    }
    public function checkFocSupervision(Request $request)
    {
        $supervisor = Staff::whereHas('department',function($query){
            $query->where('name', 'Catering');
        })
        ->whereHas('roles', function($query){
            $query->whereIn('name', ['Supervisor', 'Manager']);
        })
        ->where('phone_number', $request->phone_number)->first();

        if(!$supervisor){
            ResponseMessage('No supervisor found', 404);
        }
        if(Hash::check($request->password,$supervisor->getAuthPassword())){
            ResponseMessage('Supervisor confirmed');
        }else{
            ResponseMessage('Supervisor authorization failed', 403);
        }
    }
}
