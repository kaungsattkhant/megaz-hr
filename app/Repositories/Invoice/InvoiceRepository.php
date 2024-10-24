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
use App\Models\EntitySession;
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
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
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


    public function entitySessionLeftTime($entity_session_id)
    {
        $entitySession = EntitySession::find($entity_session_id);
        if (!$entitySession) {
            ResponseMessage('Entity is invalid', 419);
        }
        $startTime = Carbon::parse($entitySession->start_time);
        $endTime = Carbon::parse($entitySession->end_time);
        $now = Carbon::now();

        $remainingTime = $now->diff($endTime);
        if ($remainingTime->h > 1) {
            ResponseMessage("Selected Session is not available because selected session time is not available", 422);
        }
        $totalRemainingMinutes = ($remainingTime->h * 60) + $remainingTime->i;
        $leftHours = $totalRemainingMinutes / 60;
        return round($leftHours, 2);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            if ($data['entity_id'] != null) {
                $tableInvoice = $this->createInvoiceForTable($data);
                DB::commit();
                return $tableInvoice;
            }
            if ($data['entity_session_id'] != null) {
                $entitySession = null;
                if ($data['is_waiter'] === 1) {
                    $current_time = Carbon::now()->format('H:i');
                    $entitySession = EntitySession::where('entity_id', $data['entity_id'])
                        ->whereTime('start_time', '<=', $current_time) // Check if start_time is less than or equal to current time
                        ->whereTime('end_time', '>=', $current_time) // Check if end_time is greater than or equal to current time
                        ->first();
                    $entity = Entity::find($data['entity_id']);
                } else if (isset($data['entity_session_id'])) {
                    $entitySession = EntitySession::find($data['entity_session_id']);
                    $entity = Entity::find($entitySession->entity_id);
                }
                if (!isset($data['head_count_id'])) {
                    $headCount = $this->headCountCreate($data);
                    $data['head_count_id'] = $headCount->id;
                }
                $leftEntitySession = $this->entitySessionLeftTime($entitySession->id);
                if ($data['type'] == 'package') {
                    $package = Package::find($data['package_id']);
                    if (!$package) {
                        ResponseMessage('Package not found', 404);
                    }

                    $end_date = Carbon::now()->addHours($package->free_session + $package->pay_session);
                    $data['total_session_price'] = $package->pay_session * $package->session_price;
                    $data['paid_amount'] = $package->price;
                    $data['package_id'] = $package->id;
                    $data['session_duration'] = $package->pay_session + $package->free_session; // nullable

                    $roomSessionData['price'] = $package->pay_session * $package->session_price; // room session
                    $data['invoice_type'] = 'package';
                    $data['total'] = 0;
                } else if ($data['type'] == 'session') {
                    $end_date = Carbon::now()->addMinutes($data['session_duration'] * 60);
                    $data['total_session_price'] = $data['session_duration'] * $entity->price_per_hour;
                    $roomSessionData['price'] = $data['total_session_price'];
                    $data['total'] = $data['total_session_price'];
                    $data['invoice_type'] = 'session';
                } else if ($data['type'] == 'endless_time') {
                    $end_date = Carbon::now()->addMinute(1 * 60);
                    $data['session_duration'] = 1;
                    $data['total_session_price'] = $data['session_duration'] * $entity->price_per_hour;
                    $roomSessionData['price'] = $data['total_session_price'];
                    $data['total'] = $data['total_session_price'];
                    $data['invoice_type'] = 'endless_time';
                    $data['session_duration'] = 1;
                }
                $data['area_id'] = $entity->area_id;
                $data['created_by'] = UserData()->id;
                $data['invoice_date'] = Carbon::now();
                $invoice = Invoice::create($data);
                $invoice->invoice_id = sprintf('%05d', $invoice->id);
                $invoice->save();

                $roomSessionData['invoice_id'] = $invoice->id;
                $roomSessionData['entity_session_id'] = $entitySession->id;
                $roomSessionData['session_duration'] = $data['session_duration'] ?? null;
                $roomSessionData['end_date'] = $end_date->format('Y-m-d H:i:s');
                $roomSessionData['start_date'] = Carbon::now();

                if ($leftEntitySession >= 0) {
                    $leftDuration = $data['session_duration'] - $leftEntitySession;
                    $sessionsToDeactivate = ceil($leftDuration);

                    $wholeHours = floor($leftDuration);
                    $fraction = $leftDuration - $wholeHours;

                    $durations = [];
                    $specificValue = $leftEntitySession;
                    $durations[0] = $specificValue;

                    for ($i = 1; $i < $wholeHours + 1; $i++) {
                        $durations[$i] = 1;
                    }

                    if ($fraction > 0) {
                        $durations[] = round($fraction, 2);
                    }


                    if ($leftEntitySession > -1 || $leftEntitySession < 0) {
                        $sessionsToDeactivate += 1;
                    }

                    $nextSessions = EntitySession::where('id', '>=', $entitySession->id)
                        ->orderBy('id')
                        ->take($sessionsToDeactivate)
                        ->get();
                    foreach ($nextSessions as $session) {
                        if ($session->is_active == 1) {
                            ResponseMessage('Session is not availale', 422);
                        }
                        $session->is_active = 1;
                        $session->save();
                    }

                    $loopStartTime = $roomSessionData['end_date'];
                    // Ensure that $loopStartTime is a Carbon instance
                    $loopStartTime = Carbon::now();

                    foreach ($nextSessions as $index => $session) {
                        // Handle the first session

                        // Ensure $index exists in $durations
                        if (isset($durations[$index])) {
                            if ($invoice->type == 'package') {
                                $roomSession['price'] = $durations[$index] * $package->session_price;
                            } else {
                                $roomSession['price'] = $durations[$index] * $entity->price_per_hour;
                            }
                            $roomSession['invoice_id'] = $invoice->id;
                            $roomSession['entity_session_id'] = $session->id;

                            $roomSession['start_date'] = $loopStartTime;

                            $roomSession['end_date'] = $roomSession['start_date']->copy()->addHours($durations[$index]);
                            $roomSession['session_duration'] = $durations[$index];

                            RoomSession::create($roomSession);

                            $loopStartTime = $roomSession['end_date'];
                        }
                    }
                } else {
                    ResponseMessage('Room is not available', 422);
                }
                if ($data['is_waiter'] == 1) {
                    $entity->is_active = 0;
                    $entity->status = 'pending';
                    $entity->save();
                } else {
                    $entity->is_active = 1;
                    $entity->status = 'active';
                    $entity->save();
                }
                $invoice->room_session = $roomSession;
                $customer = Customer::find($invoice->customer_id);
                //change ksk
                if ($data['type'] == 'package' && $invoice) {
                    $orderData['invoice_id'] = $invoice->id;
                    $orderData['menuArray'] = json_decode($data['orders'], true);
                    $order = $this->orderService->createMultipleOrder($orderData);
                    if (isset($data['is_waiter'])) {
                        if ($data['is_waiter'] == 1) {
                            broadcast(new RoomNotificationRequest($customer, $entity, $invoice, UserData()->department_id, $order['order'], $order['orderItems']));
                        }
                    }
                }
                DB::commit();
                $returnData = [
                    'customer' => $customer,
                    'invoice' => $invoice,
                    'entity' => $entity,
                ];
                return $returnData;
            }
            //change

        } catch (\Throwable $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createInvoiceForTable($data)
    {
        // $data['area_id'] = $entity->area_id;
        $entity=Entity::find($data['entity_id']);
        $entity->is_active=1;
        $entity->status='active';
        $entity->save();
        
        $data['created_by'] = UserData()->id;
        $data['entity_id']=$data['entity_id'];
        $data['invoice_date'] = Carbon::now();
        $invoice = Invoice::create($data);
        $invoice->invoice_id = sprintf('%05d', $invoice->id);
        $invoice->save();
        return $invoice;
    }


    public function updateData(array $data, int $id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->update($data);
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


    // public function addSessionDurations(array $data)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $roomAndSession = RoomSession::where('invoice_id', $data['invoice_id'])->latest()->first();
    //         $roomSessionsWithInvoice = RoomSession::where('invoice_id', $data['invoice_id'])->get();
    //         $originalDuration = 0;
    //         foreach ($roomSessionsWithInvoice as $room_session) {
    //             $originalDuration += $room_session->session_duration;
    //         }
    //         $invoice = Invoice::find($data['invoice_id']);
    //         if ($invoice->invoice_type == 'endless_time') {
    //             ResponseMessage('Room with session and package can only be added duration', 422);
    //         }

    //         $invoice->total_session_price += $data['session_duration'] * $roomAndSession->entity->price_per_hour;

    //         if ($data['session_duration'] >= 1) {
    //             $sessionDuration = (float) $data['session_duration'];
    //             $end_date = Carbon::parse($roomAndSession->end_date)->addHours($sessionDuration);
    //         } else {
    //             $sessionDuration = (float) $data['session_duration'];
    //             $end_date = Carbon::parse($roomAndSession->end_date)->addMinutes($sessionDuration * 60);
    //         }

    //         $roomAndSession->price += $data['session_duration'] * $roomAndSession->entity->price_per_hour;
    //         $roomAndSession->end_date = $end_date->format('Y-m-d H:i:s');
    //         $roomAndSession->session_duration += $data['session_duration'];
    //         $roomAndSession->save();
    //         $invoice->save();

    //         DB::commit();
    //         return $roomAndSession;
    //     } catch (\Throwable $e) {
    //         DB::rollback();
    //         ResponseMessage($e->getMessage(), 402);
    //         throw $e;
    //     }
    // }

    public function addSessionDuration(array $data)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($data['invoice_id']);

            if ($invoice->invoice_type == 'endless_time') {
                ResponseMessage('Room with session and package can only be added duration', 422);
            }

            $roomSession = $invoice->latestSession;

            $nextSessions = EntitySession::where('id', '>', $roomSession->entity_session_id)
                ->orderBy('id')
                ->take($data['session_duration'])
                ->get();
            foreach ($nextSessions as $session) {
                if ($session->is_active == 1) {
                    ResponseMessage('Session is not available', 422);
                }
                $session->update(['is_active' => 1]);
            }
            $durations = array_fill(0, $data['session_duration'], 1);
            foreach ($nextSessions as $index => $session) {
                RoomSession::create([
                    'price' => $roomSession->entitySession->entity->price_per_hour * $durations[$index],
                    'invoice_id' => $invoice->id,
                    'entity_session_id' => $session->id,
                    'start_date' => $roomSession->end_date,
                    'end_date' => Carbon::parse($roomSession->end_date)->addHours($durations[$index]),
                    'session_duration' => $durations[$index]
                ]);
            }

            $invoice->increment('total_session_price', $data['session_duration'] * $roomSession->entitySession->entity->price_per_hour);
            DB::commit();
            return $roomSession;
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }

    public function invoiceEntityChange(array $data)
    {
        DB::beginTransaction();
        try {

            $invoice = Invoice::find($data['invoice_id']);
            $roomSessions = RoomSession::where('invoice_id', $invoice->id)->get();
            $newEntity = Entity::find($data['entity_id']);
            $latestRoomSession = $roomSessions->last();
            $current_time = Carbon::now();
            $entitySession = EntitySession::where('entity_id', $latestRoomSession->entitySession->entity_id)
                ->whereTime('start_time', '<=', $current_time)
                ->whereTime('end_time', '>=', $current_time)
                ->first();


            $previousEntity = Entity::find($entitySession->entity_id);
            $firstRoomSession = $roomSessions->first();
            $lastRoomSession = $roomSessions->last();
            $endTime = Carbon::parse($lastRoomSession->end_date);
            $sessionStartTime = Carbon::parse($firstRoomSession->start_date);
            $useHours = $sessionStartTime->diffInHours(Carbon::now());

            $totalDuration = $roomSessions->sum('session_duration');
            $leftDuration = $totalDuration - $useHours;
            $remainingEntitySessions = EntitySession::whereIn('id', $roomSessions->pluck('entity_session_id'))
                ->where('id', '>=', $entitySession->id)
                ->where('is_active', 1)
                ->get();

            $deleteEntitySessions = EntitySession::whereIn('id', $roomSessions->pluck('entity_session_id'))
                ->where('id', '>', $entitySession->id)
                ->where('is_active', 1)
                ->get();



            $nextEntitySessions = [];
            foreach ($remainingEntitySessions as $nextSession) {
                $nextEntitySession = EntitySession::where('entity_id', $data['entity_id'])->where('start_time', $nextSession->start_time)->where('end_time', $nextSession->end_time)->first();
                if ($nextEntitySession->is_active == 1) {
                    ResponseMessage('Session is not available', 422);
                }
                $nextEntitySessions[] = $nextEntitySession;
            }


            // $matchingSessions = EntitySession::where('start_time', '>', $entitySession->start_time)
            //     ->where('end_time',)
            //     ->where('entity_id', $data['entity_id'])
            //     ->take(ceil($leftDuration))
            //     ->get();

            // dd($remainingEntitySessions,$matchingSessions);

            foreach ($nextEntitySessions as $nextSession) {
                $nextSession->is_active = 1;
                $nextSession->save();
            }

            foreach ($remainingEntitySessions as $leftSession) {
                $leftSession->is_active = 0;
                $leftSession->save();
            }
            $selectedRoomSession = $entitySession->roomSession;
            $diffHours = Carbon::parse($selectedRoomSession->start_date)->diffInHours(Carbon::now());

            $selectedRoomSession->session_duration = number_format($diffHours, 2);
            $selectedRoomSession->price = $diffHours * $newEntity->price_per_hour;
            $selectedRoomSession->end_date = Carbon::parse($selectedRoomSession->start_date)->addHours($diffHours);
            $selectedRoomSession->save();

            $wholeHours = floor($leftDuration);
            $fractionalHours = $leftDuration - $wholeHours;

            $leftSession = [];

            for ($i = 0; $i < $wholeHours; $i++) {
                $leftSession[] = 1.0;
            }
            if ($fractionalHours > 0) {
                $leftSession[] = number_format($fractionalHours, 2);
            }

            $newEntity = Entity::find($data['entity_id']);
            $loopEndTime = 0;
            // dd([
            //     'total duration' => $totalDuration,
            //     'left session' => $leftSession,
            //     'diff hours' => number_format($diffHours,2),
            //     'matching sessions' => $nextEntitySessions,
            //     'remaining entity sessions' => $remainingEntitySessions,
            //     'used hours' => $useHours,
            //     'left duration' => $leftDuration,

            // ]);

            foreach ($leftSession as $index => $session) {
                $session = (float) $session;
                $newRoomSession['invoice_id'] = $data['invoice_id'];
                if ($index == 0) {
                    $newRoomSession['start_date'] = $selectedRoomSession->end_date;
                } else {
                    $newRoomSession['start_date'] = $loopEndTime;
                }
                $newRoomSession['end_date'] = Carbon::parse($newRoomSession['start_date'])->addHours((float) $session);
                $newRoomSession['session_duration'] = $session;
                $newRoomSession['entity_session_id'] = $nextEntitySessions[$index]->id;
                $newRoomSession['price'] = $session * $newEntity->price_per_hour;
                RoomSession::create($newRoomSession);
                $loopEndTime = $newRoomSession['end_date'];
            }

            foreach ($deleteEntitySessions as $deleteEntitySession) {
                $deleteEntitySession->roomSession->delete();
            }

            $newEntity->status = 'active';
            $newEntity->is_active = 1;
            $newEntity->save();

            $previousEntity->status = 'inactive';
            $previousEntity->is_active = 0;
            $previousEntity->save();
            DB::commit();
            ResponseData($invoice, 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
            $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->with(['entitySession.entity'])->get();
            $total_duration = 0;
            $total_session_value = 0;

            foreach ($roomSessions as $room) {
                $total_session_value += $room->price;
                $total_duration += $room->session_duration ?? 0;
            }

            $entity = Entity::find($latestRoomSession->entitySession->entity_id);

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
                if ($customerTotal >= $level->amount) {
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
            $invoice = Invoice::find($data['invoice_id']);
            $customer = Customer::find($invoice->customer_id);
            $invoice_id = $invoice->invoice_id;
            $lastRoomSession = $invoice->latestSession;
            $entity = Entity::find($lastRoomSession->entitySession->entity_id);

            $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->get();
            if ($invoice->invoice_type != 'package') {
                foreach ($roomSessions as $room) {
                    $total_session_price += $room->price;
                }
            }
            $order = Order::where('invoice_id', $invoice->id)->first();
            if ($order) {
                $allSold = $order->orderItems->every(function ($item) {
                    return $item->status === 'done';
                });
                if ($allSold == false) {
                    ResponseMessage('Not all order items are done', 422);
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
            $entity->save();
            $data['invoice_id'] = $invoice_id;

            $invoice->update($data);

            foreach ($roomSessions as $session) {
                $entitySession = $session->entitySession;
                if ($entitySession) {
                    $entitySession->is_active = 0;
                    $entitySession->save();
                    Log::info('it working on ' . $entitySession->id);
                }
            }

            $soldStaff = Staff::find($invoice->created_by);
            $firstRole = $soldStaff->roles->first();
            TargetPositionResult::create([
                'role_id' => $firstRole->id,
                'date_time' => CurrentTime(),
                'invoice_id' => $invoice->id,
                'amount' => $data['total']
            ]);

            if ($order != null) {
                $orderItems = $order->orderItems;
                // $groupedOrderItems = $orderItems->groupBy('menu_id')->map(function ($items) {
                //     return $items->sum('quantity');
                // });
                // dd($groupedOrderItems);
                $groupedOrderItems = $orderItems
                    ->groupBy(function ($item) {
                        return $item['menu_id'] . '-' . $item['area_id'];
                    })
                    ->map(function ($items) {
                        return [
                            'menu_id' => $items->first()->menu_id, // Access as an object
                            'area_id' => $items->first()->area_id, // Access as an object
                            'quantity' => $items->sum('quantity'),  // Sum the quantities
                        ];
                    })
                    ->values();

                foreach ($groupedOrderItems as $orderItem) {
                    TargetMenuResult::create([
                        'date_time' => CurrentTime(),
                        'invoice_id' => $invoice->id,
                        'area_id' => $orderItem['area_id'],
                        'menu_id' => $orderItem['menu_id'],
                        'quantity' => $orderItem['quantity'],
                    ]);
                }
            }


            // $this->ledgerAndTransactionForInvoice([
            //     'payment_type' => 'cash',
            //     'invoice_id' => $invoice->id,
            //     'food_charge' => $foodCharge,
            //     'beverage_charge' => $beverageCharge,
            //     'total_session_price' => $total_session_price,
            //     'service_charge' => $service_charge,
            //     'tax' => $tax,
            //     'discount_total' => $data['discount_total'],
            // ]);
            $catering_department = Department::where('name', 'Catering')->first();
            $msg = "The {$entity->name} is now closed. Thank you.";

            $role = Role::where('name', 'Staff')->where('department_id', $catering_department->id)->first();
            broadcast(new RoomDoneNotificationRequest($entity, $msg, $role->id));

            $entity->is_active = 0;
            $entity->status = 'inactive';
            $entity->save();

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
            $entity = Entity::find($latestSession->entitySession->entity_id);
            $roomSession = RoomSession::where('invoice_id', $data['invoice_id'])->get();

            if ($data['is_confirm'] == 1) {
                foreach ($roomSession as $session) {
                    $session->entitySession->is_active = 1;
                    $session->save();
                }
                $entity->is_active = 1;
                $entity->status = 'active';
            } else {
                foreach ($roomSession as $session) {
                    $session->entitySession->is_active = 0;
                    $session->entitySession->save();
                }
                $entity->is_active = 0;
                $entity->status = 'inactive';
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


        $debitLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $debit_total,
            'transaction_id' => $transaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
            'is_cashier_confirmed' => 1
        ]);
    }
}
