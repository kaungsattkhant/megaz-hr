<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Action\SendNotification\SendNotification;
use App\Models\Menu;
use App\Models\Pack;
use App\Models\Staff;
use Illuminate\Http\Request;

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
            if($latestMenuServiceDiscount)
            {
                $discountAmount = $latestMenuServiceDiscount->discount_price * $data['quantity'];
                $data['menu_service_discount_id'] = $latestMenuServiceDiscount->id;
                $data['discount_value'] = $latestMenuServiceDiscount->discount_price * $data['quantity'];
            }else{
                $discountAmount = 0;
            }
            if ($order) {
                $order->total_quantity += $data['quantity'];
                $order->total_discount_price += $discountAmount;
                $order->total += $data['original_price'] * $data['quantity'];
                $order->total_discount_price += $discountAmount;
                $order->update($data);

                $data['date'] = currentTime();
                $data['order_id'] = $order->id;
                $data['price'] = $data['original_price'] * $data['quantity'];
                $order_items = OrderItem::create($data);

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
                DB::commit();
                // $users =  $this->getUserByRole('Kitchen', ['staff']);
                // $title = 'New Order Arrived';

                // $data = [
                //     'date' => CurrentTime(),
                //     'title' => $title,
                //     'body' => 'New Order arrived to kitchen',
                // ];
                // $this->send($order_items, $users, $data);

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

            foreach ($data['menuArray'] as $menuData) {
                $menuCategoryId = $menuData['menu_category_id'];
                $price = $menuData['original_price'] * $menuData['quantity'];

                if (!isset($categorySums[$menuCategoryId])) {
                    $categorySums[$menuCategoryId] = 0;
                }

                $categorySums[$menuCategoryId] += $price;
                $menuData['invoice_id'] = $invoiceId;

                $menu = Menu::find($menuData['menu_id']);
                $latestMenuServiceDiscount = null;
                if(!isset($data['order_type']))
                {
                    $latestMenuServiceDiscount = $menu->menuServiceDiscounts()
                    ->whereDate('from_date', '<=', CurrentDate())
                    ->whereDate('to_date', '>=', CurrentDate())
                    ->orderBy('created_at', 'desc')
                    ->where('type', 'menu')
                    ->first();
                }
                if ($latestMenuServiceDiscount) {
                    $discountAmount = $latestMenuServiceDiscount->discount_price * $menuData['quantity'];
                    $totalDiscount += $discountAmount;
                    $menuData['menu_service_discount_id'] = $latestMenuServiceDiscount->id;
                    $menuData['discount_value'] = $latestMenuServiceDiscount->discount_price * $menuData['quantity'];
                } else {
                    $discountAmount = 0;
                }
                if ($order) {
                    $order->total_quantity += $menuData['quantity'];
                    $order->total_discount_price += $discountAmount; // update total discount only for this order
                    $order->total += $menuData['original_price'] * $menuData['quantity'];
                    if(isset($data['order_type']))
                    {
                        $order->total = 0;
                    }
                    $order->update($menuData);

                    $originalOrderItem = OrderItem::where('menu_id', $menuData['menu_id'])->where('order_id', $order->id)->first();

                    $menuData['date'] = CurrentTime();
                    $menuData['order_id'] = $order->id;
                    $menuData['price'] = $menuData['original_price'] * $menuData['quantity'];
                    if(isset($data['order_type']))
                    {
                        $menuData['price'] = 0;
                    }
                    $order_items = OrderItem::create($menuData);
                } else {

                    $menuData['date'] = CurrentTime();
                    $menuData['total'] = $menuData['original_price'] * $menuData['quantity'];
                    $menuData['total_quantity'] = $menuData['quantity'];
                    $menuData['total_discount_price'] = $discountAmount; // update total discount only for this order
                    $order = Order::create($menuData);
                    $order->update(['order_id' => sprintf('%05d', $order->id)]);

                    $menuData['order_id'] = $order->id;
                    if(isset($data['order_type']))
                    {
                        $menuData['price'] = 0;
                    }
                    $order_items = OrderItem::create($menuData);
                }
            }
            // $users = $this->getUserByRole('Kitchen', ['staff']);
            // $title = 'New Order Arrived';

            // $notificationData = [
            //     'date' => CurrentTime(),
            //     'title' => $title,
            //     'body' => 'New Order arrived to kitchen',
            // ];
            // $this->send($order_items, $users, $notificationData);

            DB::commit();
            return $order;
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
            if ($data['status'] == 'done') {
                $packs = Pack::where('menu_id', $orderItem->menu_id)->where('status', 'ready')->where('expired_at', '>', CurrentTime())->orderBy('expired_at', 'asc')->take($orderItem->quantity)->get();
                if (count($packs) < $orderItem->quantity) {
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
            $users = collect([]);
            $users =  $this->getUserByRole('Catering', ['staff']);
            $title = 'Order Item Status Update';

            $data = [
                'date' => CurrentTime(),
                'title' => $title,
                'body' => 'Order Item status is changed by Kitchen Department',
            ];
            $this->send($orderItem, $users, $data);
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

            $totalCount = OrderItem::with('menu', 'order.invoice.room')->whereBetween('created_at', [$startTime, $endTime])->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $order_items = OrderItem::with('menu', 'order.invoice.room')
                ->whereBetween('date', [$startTime, $endTime])
                ->skip($skip)
                ->take($perPage)
                ->get();
            $paginationData = MakePaginationData($request, $totalCount, 'order_items');
            $paginationData['order_items'] = $order_items;

            return $paginationData;
        } else {
            if ($request->date) {
                $startTime = $request->date . ' 00:00:00';
                $endTime = $request->date . ' 23:59:59';
                $orderItems = OrderItem::with('menu', 'order.invoice.room')->whereBetween('date', [$startTime, $endTime])->get();
            } else {
                $orderItems = OrderItem::with('menu', 'order.invoice.room')->get();
            }

            return $orderItems;
        }
    }
}
