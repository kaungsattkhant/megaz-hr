<?php

namespace App\Repositories\Invoice;

use App\Events\RoomDoneNotificationRequest;
use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\BirthdayPromotion;
use App\Models\Customer;
use App\Models\CustomerLevelDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Events\RoomNotificationRequest;
use App\Events\WaiterNotificationRequest;
use App\Models\Department;
use App\Models\Entity;
use App\Models\HeadCount;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\RoomDiscount;
use App\Models\RoomSession;
use App\Models\Staff;
use App\Models\TargetMenuResult;
use App\Models\TargetPosition;
use App\Models\TargetPositionResult;
use App\Models\User;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;


class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Invoice::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;

            if ($request->date) {
                $invoices = Invoice::with('customer', 'room')
                    ->whereBetween('created_at', [$request->date . ' 00:00:00', $request->date . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->skip($skip)
                    ->take($perPage)
                    ->get();
            } else {
                $invoices = Invoice::with('customer', 'room')
                    ->orderBy('created_at', 'desc')
                    ->skip($skip)
                    ->take($perPage)
                    ->get();
            }
            $paginationData = MakePaginationData($request, $totalCount, 'invoices');
            $paginationData['invoices'] = $invoices;
            $paginationData = MakePaginationData($request, $totalCount, 'invoices');
            $paginationData['invoices'] = $invoices;

            return $paginationData;
        } else {
            if ($request->date) {
                $invoices = Invoice::with('customer', 'room')
                    ->whereBetween('created_at', [$request->date . ' 00:00:00', $request->date . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $invoices = Invoice::with('customer', 'room')->get();
            }

            return $invoices;
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $entity = Entity::find($data['entity_id']);
            if ($entity->is_active == 1) {
                ResponseMessage('Room is not available', 422);
            }
            $data['area_id'] = $entity->area_id;
            if (!isset($data['head_count_id'])) {
                $headCount = $this->headCountCreate($data);
                $data['head_count_id'] = $headCount->id;
            }

            $data['created_by'] = UserData()->id;
            if ($data['type'] == 'package') {
                $package = Package::find($data['package_id']);
                if (!$package) {
                    ResponseMessage('Package not found', 403);
                }

                $invoiceDate = Carbon::parse($data['invoice_date']);
                if ($invoiceDate->lt($package->from_date) || $invoiceDate->gt($package->to_date)) {
                    ResponseMessage('The selected package is not available for the given date.', 422);
                }

                $end_date = Carbon::parse($data['invoice_date'])->addHours($package->free_session + $package->pay_session);
                $data['total_session_price'] = $package->pay_session * $package->session_price;
                $data['paid_amount'] = $package->price;
                $data['package_id'] = $package->id;
                $data['session_duration'] = $package->pay_session + $package->free_session; // nullable

                $roomSessionData['price'] = $package->pay_session * $package->session_price; // room session
                $data['invoice_type'] = 'package';

                $data['total'] = 0;
            } else if ($data['type'] == 'session') {

                $end_date = Carbon::parse($data['invoice_date'])->addMinutes($data['session_duration'] * 60);
                $data['total_session_price'] = $data['session_duration'] * $entity->price_per_hour;
                $roomSessionData['price'] = $data['total_session_price'];
                $data['total'] = $data['total_session_price'];
                $data['invoice_type'] = 'session';
            } else if ($data['type'] == 'endless_time') {
                $data['invoice_type'] = 'endless_time';
                $end_date = null;
            }
            $invoice = Invoice::create($data);
            $invoice->invoice_id = sprintf('%05d', $invoice->id);
            $invoice->save();

            if (isset($data['is_waiter']) && $data['is_waiter'] == 1) {
                $entity->status = 'pending';
                $entity->is_active = 0;
            } else {
                $entity->status = 'active';
                $entity->is_active = 1;
            }

            $entity->save();

            $roomSessionData['invoice_id'] = $invoice->id;
            $roomSessionData['entity_id'] = $entity->id;
            $roomSessionData['session_duration'] = $data['session_duration'] ?? null;

            if ($end_date == null) {
                $roomSessionData['end_date'] = null;
            } else {
                $roomSessionData['end_date'] = $end_date->format('Y-m-d H:i:s');
            }

            $roomSessionData['start_date'] = $data['invoice_date'];
            $roomSession = RoomSession::create($roomSessionData);
            $invoice->room_session = $roomSession;
            $customer = Customer::find($invoice->customer_id);
            if (isset($data['is_waiter'])) {
                if ($data['is_waiter'] == 1 && $invoice) {
                    broadcast(new RoomNotificationRequest($customer, $entity, $roomSession, $invoice, UserData()->department_id));
                }
            }
            DB::commit();
            return $invoice;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }


    public function updateData(array $data, int $id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->updaet($data);
            return $invoice;
        }

        return $invoice;
    }

    public function deleteData(int $id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->delete();
            return true;
        }
        return false;
    }

    public function headCountCreate(array $data)
    {
        $data['total_head_count'] = $data['female'] + $data['male'] + $data['child'];
        $headCount = HeadCount::create($data);
        return $headCount;
    }


    public function addSessionDuration(array $data)
    {
        DB::beginTransaction();
        try {
            $roomAndSession = RoomSession::where('invoice_id', $data['invoice_id'])->latest()->first();
            $roomSessionsWithInvoice = RoomSession::where('invoice_id', $data['invoice_id'])->get();
            $originalDuration = 0;
            foreach ($roomSessionsWithInvoice as $room_session) {
                $originalDuration += $room_session->session_duration;
            }
            $invoice = Invoice::find($data['invoice_id']);
            if ($invoice->invoice_type == 'endless_time') {
                ResponseMessage('Room with session and package can only be added duration', 422);
            }

            $invoice->total_session_price += $data['session_duration'] * $roomAndSession->entity->price_per_hour;

            if ($data['session_duration'] >= 1) {
                $sessionDuration = (float) $data['session_duration'];
                $end_date = Carbon::parse($roomAndSession->end_date)->addHours($sessionDuration);
            } else {
                $sessionDuration = (float) $data['session_duration'];
                $end_date = Carbon::parse($roomAndSession->end_date)->addMinutes($sessionDuration * 60);
            }

            $roomAndSession->price += $data['session_duration'] * $roomAndSession->entity->price_per_hour;
            $roomAndSession->end_date = $end_date->format('Y-m-d H:i:s');
            $roomAndSession->session_duration += $data['session_duration'];
            $roomAndSession->save();
            $invoice->save();

            DB::commit();
            return $roomAndSession;
        } catch (\Throwable $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function invoiceEntityChange($data)
    {
        DB::beginTransaction();
        try {

            $invoice = Invoice::find($data['invoice_id']);
            if ($invoice->invoice_type == 'package') {
                $package = Package::find($invoice->package_id);
            }
            $lastRoomwithInvoice = $invoice->latestSession;
            // caculating the possible left duration of session
            $startTime = Carbon::parse($lastRoomwithInvoice->start_date);
            $endTime = Carbon::now();
            $durationInMinute = $startTime->diffInMinute($endTime);
            $durationInHours = $durationInMinute / 60; // Convert minutes to hours
            $roundedDurationInHours = round($durationInHours, 2);

            $lastEntity = Entity::find($lastRoomwithInvoice->entity_id);

            if ($invoice->invoice_type == "endless_time") {
                $leftDuration = null;
            } else if($invoice->invoice_type == 'session') {
                $leftDuration = $lastRoomwithInvoice->session_duration - ceil($roundedDurationInHours);
            }else{
                $leftDuration = $lastRoomwithInvoice->session_duration - $roundedDurationInHours;
            }

            if ($invoice->invoice_type == 'endless_time') {
                $lastRoomwithInvoice->price = ceil($roundedDurationInHours) * $lastEntity->price_per_hour;
                $lastRoomwithInvoice->session_duration = ceil($roundedDurationInHours);
                $lastRoomwithInvoice->end_date = CurrentTime();
                $lastRoomwithInvoice->save();
            } else if ($leftDuration >= 1) {
                if ($invoice->invoice_type != 'package') {
                    $lastRoomwithInvoice->price = ceil($roundedDurationInHours) * $lastEntity->price_per_hour;
                    $lastRoomwithInvoice->session_duration = ceil($roundedDurationInHours);
                } else {
                    $lastRoomwithInvoice->session_duration = $roundedDurationInHours;
                }
                    $lastRoomwithInvoice->end_date = CurrentTime();
                $lastRoomwithInvoice->save();
            }

            $lastEntity->is_active = 0;
            $lastEntity->status = 'inactive';
            $lastEntity->save();

            $selectedEntity = Entity::find($data['entity_id']);
            if ($selectedEntity->is_active == 1) {
                ResponseMessage('Room is currently active. Please choose another room', 422);
            }

            $selectedEntity->is_active = 1;
            $selectedEntity->status = 'active';
            $selectedEntity->save();

            $newSession['start_date'] = Carbon::now();

            $newSession['invoice_id'] = $invoice->id;
            $newSession['session_duration'] = ceil($leftDuration);
            if ($invoice->invoice_type == 'package') {
                $newSession['price'] = 0;
                $newSession['session_duration'] = $leftDuration;
            } else {
                $newSession['price'] = $selectedEntity->price_per_hour * ceil($leftDuration);
            }
            $newSession['entity_id'] = $selectedEntity->id;

            if ($invoice->invoice_type == 'endless_time') {
                $newSession['end_date'] = null;
                $newSession['session_duration'] = null;
                $newSession['price'] = 0;
            } else {
                if ($leftDuration >= 1) {

                    $end_date = Carbon::parse($newSession['start_date'])->addHours($leftDuration);
                    $newSession['end_date'] = $end_date->format('Y-m-d H:i:s');
                } else {
                    $end_date = Carbon::parse($newSession['start_date'])->addHours($leftDuration);
                    $newSession['end_date'] = $end_date->format('Y-m-d H:i:s');
                }
            }
            if ($leftDuration == null) {
                $newSession['start_date'] = Carbon::now();
                $newSession['end_date'] = null;
                $newSession['session_duration'] = 0;
                $newSession['price'] = 0;
                $newSession['invoice_id'] = $invoice->id;
                $newSession['entity_id'] = $selectedEntity->id;
                $newSession = RoomSession::create($newSession);
            } else if ($leftDuration < 1) {
                $newSession['start_date'] = $lastRoomwithInvoice->end_date;
                $newSession['end_date'] = $lastRoomwithInvoice->end_date;
                $newSession['session_duration'] = 0;
                $newSession['price'] = 0;
                $newSession['invoice_id'] = $invoice->id;
                $newSession['entity_id'] = $selectedEntity->id;
                $newSession = RoomSession::create($newSession);
            } else {
                $newSession = RoomSession::create($newSession);
            }

            $invoice->area_id = $selectedEntity->area_id;
            $invoice->save();
            $allRooms = RoomSession::where('invoice_id', $invoice->id)->get();
            $total_session_price = 0;
            foreach ($allRooms as $room) {
                $total_session_price += $room->price;
            }
            $invoice->total_session_price = $total_session_price;

            $invoice->save();
            DB::commit();

            return $invoice;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function doneRoom(array $data)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($data['invoice_id']);

            $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
            $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->with(['entity'])->get();
            $total_duration = 0;
            $total_session_value = 0;

            foreach ($roomSessions as $room) {
                $total_session_value += $room->price;
                $total_duration += $room->session_duration ?? 0;
            }
            $entity = Entity::find($latestRoomSession->entity_id);
            if ($invoice->invoice_type == 'endless_time') {
                $latestRoomSession_end_date = Carbon::parse($latestRoomSession->start_date);
                $currentDate = Carbon::now();
                $minutesDifference = $latestRoomSession_end_date->diffInMinutes($currentDate);
                $hoursDifference = $minutesDifference / 60;
                $hoursDifference = number_format($hoursDifference, 1);

                $step = 0.5;
                $final_hour = ceil($hoursDifference / $step) * $step;

                $hoursDifference = $final_hour;

                if (($total_duration + $hoursDifference) < 1) {
                    ResponseMessage("You can't end this room before 1 hours", 402);
                }
                $data['session_duration'] = $hoursDifference;
                $data['end_date'] = CurrentTime();
                $data['price'] = $hoursDifference * $entity->price_per_hour;
                $invoice->total_session_price += ($hoursDifference - $latestRoomSession->session_duration) * $entity->price_per_hour;
                $invoice->save();
                $latestRoomSession->update($data);
            }
            $roomDoneResponse['total_session_price'] = $invoice->total_session_price;

            $today = Carbon::today();
            $customer = Customer::find($invoice->customer_id);
            $customerTotal = 0;
            if ($customer->invoices) {
                foreach ($customer->invoices as $customerInvoice) {
                    $customerTotal += $customerInvoice->total;
                }
            }

            $levels = CustomerLevelDiscount::all();
            $customerLevel = null;
            foreach ($levels as $level) {
                if ($customerTotal  >= $level->amount) {
                    $customerLevel = $level;
                } else {
                    break;
                }
            }
            if ($customerLevel !== null) {
                $roomDoneResponse['customer_level'] = $customerLevel->name;
                $roomDoneResponse['customer_level_discount_value'] = $customerLevel->promotion_value;
            } else {
                $roomDoneResponse['customer_level'] = 'no customer level';
            }

            $roomDoneResponse['customer_total'] = $customerTotal;
            if ($customer) {
                $birthdate = Carbon::parse($customer->birthdate);
                $roomDoneResponse['is_birthday'] = $birthdate->isBirthday($today);
            } else {
                $roomDoneResponse['is_birthday'] = false;
            }
            $roomDoneResponse['room_sessions'] = $latestRoomSession;
            $roomDoneResponse['rooms_sessions'] = $roomSessions;
            DB::commit();
            ResponseData($roomDoneResponse);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function doneEntityWithInvoice(array $data)
    {
        DB::beginTransaction();
        try {
            $foodCharge = 0;
            $beverageCharge = 0;
            $total_session_price = 0;
            $order_discount = 0;
            $service_charge = 0;
            $tax = 0;
            $foodDrink = 0;
            $discount_value = 0;
            $room_discount_value = 0;
            $bdDiscount = 0;
            $customerLevelDiscount = 0;
            $discount_value = 0;

            if (isset($data['customer_level_discount'])) {
                $customerLevelDiscount = $data['customer_level_discount'] ?? 0;
            }

            if (isset($data['tax'])) {
                $tax = $data['tax'];
            }

            if (isset($data['service_charge'])) {
                $service_charge = $data['service_charge'];
            }


            if (isset($data['order_discount'])) {
                $order_discount = $data['order_discount'];
            }

            if (isset($data['customer_level_discount'])) {
                $customerLevelDiscount = $data['customer_level_discount'] ?? 0;
            }

            if (isset($data['birthday_discount'])) {
                $bdDiscount = $data['birthday_discount'] ?? 0;
            }

            if (isset($data['order_categories'])) {
                $data['order_categories'] = json_decode($data['order_categories'], true);
                foreach ($data['order_categories'] as $menu) {
                    if ($menu['menu_category_id'] == 1 || $menu['menu_category_id'] == 2 || $menu['menu_category_id'] == 3) {
                        $foodCharge += $menu['price'];
                    } else {
                        $beverageCharge += $menu['price'];
                    }
                }
            }

            $invoice  = Invoice::find($data['invoice_id']);
            $customer = Customer::find($invoice->customer_id);
            $invoice_id = $invoice->invoice_id;
            $lastRoomSession = $invoice->latestSession;
            $entity = Entity::find($lastRoomSession->entity_id);

            $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->get();
            if ($invoice->invoice_type != 'package') {
                foreach ($roomSessions as $room) {
                    $total_session_price += $room->price;
                }
            }
            $order = Order::where('invoice_id', $invoice->id)->first();
            if ($order) {
                $allSold = $order->orderItems->every(function ($item) {
                    return $item->status === 'sold';
                });
                if($allSold==false){
                    ResponseMessage('Not all order items are sold',422);
                }
            }

            if (isset($data['discount_type'])) {
                if ($data['discount_type'] == 'room_discount') {
                    $roomDiscount = RoomDiscount::find($data['room_discount_id']);
                    if (!$roomDiscount->rooms->contains($entity->id)) {
                        ResponseMessage('Selected discount cannot be applied', 422);
                    }

                    if ($roomDiscount->session <= $lastRoomSession->session_duration) {
                        $room_discount_value = $total_session_price - $data['room_discount_amount'];
                        $total_session_price = $data['room_discount_amount'];
                        $lastRoomSession->discount_session = $data['discount_session'];
                        $lastRoomSession->save();
                    } else {
                        ResponseMessage('Discount cannot be applied', 422);
                    }
                }
            }


            $data['room_discount_value'] = $room_discount_value;
            if (isset($data['discount_value'])) {
                $discount_value = $data['discount_value'];
            }
            $data['discount_value'] = $room_discount_value + $bdDiscount + $customerLevelDiscount + $discount_value;
            $data['discount_total'] = $room_discount_value + $bdDiscount + $customerLevelDiscount;
            $data['total'] -= $room_discount_value;
            $data['tax'] = $tax;
            $data['service_charge'] = $service_charge;
            $data['total_session_price'] = $total_session_price;
            $data['order_discount_value'] = $order_discount;
            $data['sub_total'] = ($data['total']) - ($tax + $service_charge);
            $data['payment_status'] = 'received';
            $data['complete_date'] = CurrentTime();
            $entity->status = 'inactive';
            $entity->is_active = 0;
            $entity->save();
            $data['invoice_id'] = $invoice_id;

            $invoice->update($data);

            $soldStaff = Staff::find($invoice->created_by);
            $firstRole = $soldStaff->roles->first();

            TargetPositionResult::create([
                'role_id' => $firstRole->id,
                'date_time'=>CurrentTime(),
                'invoice_id' => $invoice->id,
                'amount' => $data['total']
            ]);
            $orderItems = $order->orderItems;
            $groupedOrderItems = $orderItems->groupBy('menu_id')->map(function ($items) {
                return $items->sum('quantity');
            });
            foreach($groupedOrderItems as $menu_id => $totalQuantity) {
                TargetMenuResult::create([
                    'date_time' => CurrentTime(),
                    'invoice_id' => $invoice->id,
                    'area_id' => $entity->area_id,
                    'menu_id' => $menu_id,
                    'quantity' => $totalQuantity,
                ]);
            }
            $this->ledgerAndTransactionForInvoice([
                'payment_type' => 'cash',
                'invoice_id' => $invoice->id,
                'food_charge' => $foodCharge,
                'beverage_charge' => $beverageCharge,
                'total_session_price' => $total_session_price,
                'service_charge' => $service_charge,
                'tax' => $tax,
                'discount_total' => $data['discount_total'],
            ]);
            $catering_department = Department::where('name', 'Catering')->first();
            $msg = "The {$entity->name} is now closed. Thank you.";

            $role = Role::where('name', 'Staff')->where('department_id', $catering_department->id)->first();
            broadcast(new RoomDoneNotificationRequest($entity, $msg, $role->id));
            dd('stop');
            DB::commit();
            return $invoice;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function invoiceConfirm(array $data)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($data['invoice_id']);
            $latestSession = RoomSession::where('invoice_id', $data['invoice_id'])->latest()->first();
            $entity = Entity::find($latestSession->entity_id);

            if ($data['is_confirm'] == 1) {
                $entity->status = 'active';
                $entity->is_active = 1;
            } else {
                $entity->status = 'inactive';
                $entity->is_active = 0;
                $invoice->delete();
                $latestSession->delete();
            }
            $entity->save();
            DB::commit();
            broadcast(new WaiterNotificationRequest($entity, UserData()->department_id));
            Responsemessage('Room status updated');
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function ledgerAndTransactionForInvoice(array $data)
    {
        if ($data['payment_type'] == 'cash') {
            $posBook = Account::where('account_code', '2-1011')->first();
        } else {
            $posBook = Account::where('account_code', '2-1012')->first();
        }

        // $data['date'] = now();
        // $data['created_by'] = ; //example
        // $data['transactionable_id'] = $invoice->id;
        // $data['transactionable_type'] = 'invoice';
        // $data['is_confirmed'] = 1;
        $transaction = (new StoreTransactionLedger())->createTransaction([
            'date' => now(),
            'created_by' => UserData()->id,
            'transactionable_id' => $data['invoice_id'],
            'transactionable_type' => 'invoice',
            'is_confirmed' => 1,
        ]);


        $debit_total = 0;

        if ($data['food_charge'] != 0) {
            $foodKtvAcc = Account::where('account_code', '5-0101')->first();

            if ($foodKtvAcc != null) {
                $foodCreditLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['food_charge'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $foodKtvAcc->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 1

                ]);
            }

            $debit_total += $data['food_charge'];
        }
        dd('in there');

        if ($data['beverage_charge'] != 0) {

            $beverageKtvAcc = Account::where('account_code', '5-0102')->first();

            if ($beverageKtvAcc != null) {
                $beverageCreditLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['beverage_charge'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $beverageKtvAcc->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 1

                ]);
            }

            $debit_total += $data['beverage_charge'];
        }
        dd('in there');


        if ($data['total_session_price'] != 0) {
            $ktvRoomAcc = Account::where('account_code', '5-0103')->first();
            if ($ktvRoomAcc != null) {
                $ktvRoomLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['total_session_price'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $ktvRoomAcc->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 1

                ]);
            }

            $debit_total += $data['total_session_price'];
        }
        dd('in there');


        if ($data['service_charge'] != 0) {
            $serviceMoneyAcc = Account::where('account_code', '6-2009')->first();

            if ($serviceMoneyAcc != null) {
                $serviceLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['service_charge'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $serviceMoneyAcc->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 1

                ]);
            }

            $debit_total += $data['service_charge'];
        }

        dd('in there');


        if ($data['tax'] != 0) {
            $taxAcc = Account::where('account_code', '6-9002')->first();

            if ($data['tax'] != null) {
                $taxLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['tax'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $taxAcc->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 1

                ]);
            }
            $debit_total += $data['tax'];
        }

        dd('in there');


        if ($data['discount_total'] != 0) {
            $discountAcc = Account::where('account_code', '6-2003')->first();

            if ($discountAcc != null) {
                $debit_total += $data['discount_total'];

                $debitDiscountLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['discount_total'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $discountAcc->id,
                    'action' => 'debit',
                    'is_cashier_confirmed' => 1
                ]);

                $creditDiscountLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['discount_total'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $posBook->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 1
                ]);
            }
        }

        dd('in there');

        $debitLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $debit_total,
            'transaction_id' => $transaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
            'is_cashier_confirmed' => 1
        ]);
    }
}
