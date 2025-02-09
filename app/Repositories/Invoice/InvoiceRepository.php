<?php

namespace App\Repositories\Invoice;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Entity;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Service;
use App\Models\Customer;
use App\Models\HeadCount;
use App\Models\Department;
use App\Models\RoomSession;
use App\Models\RoomDiscount;
use Illuminate\Http\Request;
use App\Models\EntitySession;
use App\Traits\CustomerTrait;
use App\Models\InvoiceService;
use App\Models\TargetPosition;
use App\Services\OrderService;
use App\Models\TargetMenuResult;
use App\Models\BirthdayPromotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TargetPositionResult;
use App\Models\CustomerLevelDiscount;
use App\Services\InvoiceModelService;
use App\Events\RoomNotificationRequest;
use App\Events\WaiterNotificationRequest;
use App\Events\RoomDoneNotificationRequest;
use App\Repositories\Order\OrderRepository;
use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Http\Action\Transaction\PurchaseOrderTransaction;
use App\Models\CustomerDeposit;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    use CustomerTrait;
    private $orderService;
    private $invoiceService;
    public function __construct(OrderService $orderService, InvoiceModelService $invoiceService)
    {
        $this->orderService = $orderService;
        $this->invoiceService = $invoiceService;
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
                $invoices = Invoice::with(['customer'])
                    ->whereBetween('created_at', [$request->date . ' 00:00:00', $request->date . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->skip($skip)
                    ->take($perPage)
                    ->get();
            } else {
                $invoices = Invoice::with(['customer'])
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
                $invoices = Invoice::with(['customer'])
                    ->whereBetween('created_at', [$request->date . ' 00:00:00', $request->date . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $invoices = Invoice::with(['customer'])->get();
            }

            foreach ($invoices as $invoice) {
                $lastRoomSession = $invoice->roomSession()->get()->last();
                $lastRoom = $lastRoomSession->entitySession->entity;
                $invoice->room = $lastRoom;
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
        $endTime = Carbon::parse($entitySession->end_time);
        $now = Carbon::now();
        $remainingTime = floor($now->diffInMinutes($endTime));
        $leftHours = $remainingTime / 60;
        // dd(round($leftHours, 2));
        return round($leftHours, 2);
    }

    public function createData(array $data)
    {
        // "entity_session_id" => "15"
        // "customer_id" => "1"
        // "start_time" => "2024-10-31T14:13"
        // "session_duration" => "2"
        // "type" => "session"
        // "deposit" => "null"
        // "female" => 3
        // "male" => 2
        // "child" => 1
        // "is_waiter" => 0
        // "entity_id" => null
        DB::beginTransaction();
        try {
            if ($data['entity_id'] != null && !$data['is_waiter']) { //create table invoice
                $tableInvoice = $this->createInvoiceForTable($data);
                DB::commit();
                return $tableInvoice;
            }
            if ((isset($data['entity_session_id']) && $data['entity_session_id'] != null) || $data['is_waiter']) { // create room invoice
                $entitySession = null;
                if (isset($data['entity_id']) && $data['entity_id'] != null) {
                    $entity = Entity::find($data['entity_id']);
                    $currentTime = Carbon::parse(now())->format('H:i');
                    $entitySession = $entity->currentEntitySession($currentTime)->first();
                    if (!$entitySession) {
                        ResponseMessage('Entity is invalid', 422);
                    }
                    $data['entity_session_id'] = $entitySession->id;
                    $entity = $entitySession->entity;
                }


                // if ($data['is_waiter'] === 1) {
                //     $current_time = Carbon::now()->format('H:i');
                //     $entitySession = EntitySession::where('entity_id', $data['entity_id'])
                //         ->whereTime('start_time', operator: '<=', $current_time) // Check if start_time is less than or equal to current time
                //         ->whereTime('end_time', '>=', $current_time) // Check if end_time is greater than or equal to current time
                //         ->first();
                //     $entity = Entity::find($data['entity_id']);
                // } 
                else if (isset($data['entity_session_id'])) {
                    $entitySession = EntitySession::find($data['entity_session_id']);
                    $entity = Entity::find($entitySession->entity_id);
                }
                if (!isset($data['head_count_id'])) {
                    $headCount = $this->headCountCreate($data);
                    $data['head_count_id'] = $headCount->id;
                }
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

                //create deposit 
                $this->storeCustomerDeposit($data, UserData()->id);
                //
                $invoice->invoice_id = sprintf('%05d', $invoice->id);
                $invoice->save();
                $roomSessionData['invoice_id'] = $invoice->id;
                $roomSessionData['entity_session_id'] = $entitySession->id;
                $roomSessionData['session_duration'] = $data['session_duration'] ?? null;
                $roomSessionData['end_date'] = $end_date->format('Y-m-d H:i:s');
                $roomSessionData['start_date'] = Carbon::now();

                $leftEntitySession = $this->entitySessionLeftTime($entitySession->id);
                $startTime = now()->format('H:i');
                $endTime = now()->addHour((int) $data['session_duration'])->format('H:i');
                // $startTime = "22:10";
                // $endTime = "00:10";

                $takeSessions = EntitySession::select('id', 'start_time', 'end_time', 'is_active', 'spans_midnight', 'entity_id')
                    ->where('entity_id', $entitySession->entity_id)
                    ->where(function ($query) use ($startTime, $endTime) {
                        if ($startTime < $endTime) {
                            // Normal case: within the same day (e.g., 15:00 - 16:00)
                            $query->whereTime('start_time', '<', $endTime)
                                ->whereTime('end_time', '>', $startTime);
                        } else {
                            // Special case: across midnight (e.g., 23:06 - 01:06)
                            $query
                                ->where(function ($q) use ($startTime, $endTime) {
                                $q->whereTime('start_time', '<', $endTime)
                                    ->orWhereTime('end_time', '>', $startTime);
                            })
                                ->orWhere(function ($q) use ($startTime, $endTime) {
                                // Case 1: Sessions starting before midnight and ending after
                                $q->whereTime('start_time', '>=', $startTime)
                                    ->orWhereTime('end_time', '<=', $endTime);
                            })
                                ->orWhere(function ($q) {
                                // Case 2: Sessions that completely span over midnight (e.g., 22:00 - 02:00)
                                $q->whereTime('start_time', '>', '23:59')
                                    ->orWhereTime('end_time', '<', '00:00');
                            });
                        }

                    })
                    //     ->orderByRaw("
                    //     CASE 
                    //         WHEN spans_midnight = 1 THEN 0 
                    //         ELSE 1 
                    //     END, start_time
                    // ")
                    ->orderByRaw("
        CASE 
            WHEN start_time >= '00:00:00' AND start_time < '12:00:00' THEN 2 -- Sessions after midnight (00:xx)
            WHEN spans_midnight = 1 THEN 1 -- Sessions spanning midnight (23:xx - 00:xx)
            ELSE 0 -- Sessions before midnight (22:xx)
        END, start_time
    ")
                    ->get();
                $startEntitySession = $takeSessions->first();
                $lastEntitySession = $takeSessions->last();
                //currenct codding is here
                foreach ($takeSessions as $session) {
                    $nowDate = now()->format('Y-m-d');
                    $roomSession['start_date'] = Carbon::parse($nowDate . ' ' . $session->start_time);
                    $roomSession['end_date'] = Carbon::parse($nowDate . ' ' . $session->end_time);
                    if ($startEntitySession->start_time == $session->start_time) {
                        $startEntitySessionLeft = $this->entitySessionLeftTime($session->id);
                        $roomSession['session_duration'] = $startEntitySessionLeft;
                        $roomSession['start_date'] = Carbon::parse($nowDate . ' ' . $startTime);
                        $roomSession['end_date'] = Carbon::parse($nowDate . ' ' . $session->end_time);
                    } elseif ($lastEntitySession->end_time == $session->end_time) {
                        $lastEntitySesionLeft = $this->entitySessionLeftTime($startEntitySession->id);
                        $roomSession['session_duration'] = 1 - $lastEntitySesionLeft;
                        $roomSession['start_date'] = Carbon::parse($nowDate . ' ' . $session->start_time);
                        $roomSession['end_date'] = Carbon::parse($nowDate . ' ' . $endTime);
                    } elseif ($startEntitySession->start_time != $session->start_time && $lastEntitySession->end_time != $session->end_time) {
                        $roomSession['session_duration'] = 1;
                    }
                    if ($invoice->type == 'package') {
                        $roomSession['price'] = $roomSession['session_duration'] * $package->session_price;
                    } else {
                        $roomSession['price'] = $roomSession['session_duration'] * $entity->price_per_hour;
                    }
                    $roomSession['invoice_id'] = $invoice->id;
                    $roomSession['entity_session_id'] = $session->id;
                    $createdRoomSesion = RoomSession::create($roomSession);
                    $session->is_active = 1;
                    $session->save();
                }
                //current coding is here 
                // if ($leftEntitySession >= 0) {
                //     // dd($data['session_duration'],$leftEntitySession);
                //     $leftDuration = (int) $data['session_duration'] - $leftEntitySession;
                //     $sessionsToDeactivate = ceil($leftDuration);
                //     $wholeHours = floor($leftDuration);
                //     $fraction = $leftDuration - $wholeHours;
                //     $durations = [];
                //     $specificValue = $leftEntitySession;
                //     $durations[0] = $specificValue;
                //     for ($i = 1; $i < $wholeHours + 1; $i++) {
                //         $durations[$i] = 1;
                //     }

                //     if ($fraction > 0) {
                //         $durations[] = round($fraction, 2);
                //     }
                //     if ($leftEntitySession > -1 || $leftEntitySession < 0) {
                //         $sessionsToDeactivate += 1;
                //     }
                //     // dd($sessionsToDeactivate);
                //     $nextSessions = EntitySession::where('id', '>=', $entitySession->id)
                //         ->orderBy('id')
                //         ->select('id', 'start_time', 'end_time', 'is_active')
                //         ->take($sessionsToDeactivate)
                //         ->get();
                //     foreach ($nextSessions as $session) {
                //         // if ($session->is_active == 1) {
                //         //     ResponseMessage('Session is not availale', 422);
                //         // }
                //         $session->is_active = 1;
                //         $session->save();
                //     }
                //     $loopStartTime = $roomSessionData['end_date'];
                //     // Ensure that $loopStartTime is a Carbon instance
                //     $loopStartTime = Carbon::now();
                //     // dd($loopStartTime);
                //     foreach ($nextSessions as $index => $session) {
                //         // Handle the first session

                //         // Ensure $index exists in $durations
                //         if (isset($durations[$index])) {
                //             if ($invoice->type == 'package') {
                //                 $roomSession['price'] = $durations[$index] * $package->session_price;
                //             } else {
                //                 $roomSession['price'] = $durations[$index] * $entity->price_per_hour;
                //             }
                //             $roomSession['invoice_id'] = $invoice->id;
                //             $roomSession['entity_session_id'] = $session->id;

                //             $roomSession['start_date'] = $loopStartTime;

                //             $roomSession['end_date'] = $roomSession['start_date']->copy()->addHours($durations[$index]);
                //             $roomSession['session_duration'] = $durations[$index];

                //             RoomSession::create($roomSession);

                //             $loopStartTime = $roomSession['end_date'];
                //         }
                //     }
                // } else {
                //     ResponseMessage('Room is not available', 422);
                // }
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
        $entity = Entity::find($data['entity_id']);
        $entity->is_active = 1;
        $entity->status = 'active';
        $entity->save();
        if (!isset($data['head_count_id'])) {
            $headCount = $this->headCountCreate($data);
            $data['head_count_id'] = $headCount->id;
        }
        $data['created_by'] = UserData()->id;
        $data['entity_id'] = $data['entity_id'];
        $data['area_id'] = $entity->area_id;
        $data['invoice_date'] = Carbon::now();
        $invoice = Invoice::create($data);
        $invoice->invoice_id = sprintf('%05d', $invoice->id);
        $invoice->save();
        $customer = null;
        if (isset($data['customer_id'])) {
            $customer = $invoice->customer;
        }
        $returnData = [
            'customer' => $customer,
            'invoice' => $invoice,
            'entity' => $entity,
        ];
        return $returnData;
        // return $invoice;
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

    public function storeCustomerDeposit($data, $userId)
    {
        $isDeposit = (bool) $data['is_deposit'];
        if ($isDeposit) {
            if ((!isset($data['cash_account_id']) || (isset($data['cash_account_id']) && $data['cash_account_id'] == null))) {
                ResponseMessage('Cash Account is required', 419);
            }
            if (!isset($data['account_id']) || (isset($data['account_id']) && $data['account_id'] == null)) {
                ResponseMessage('Customer Deposit Account is required', 419);
                // ResponseMessage(message: 'Customer Deposit Account is required',419);
            }
            $customerDeposit = CustomerDeposit::create([
                'type' => 'deposit',
                'date_time' => now(),
                'amount' => $data['deposit'],
                'account_id' => $data['account_id'],
                'cash_account_id' => $data['cash_account_id'],
                'customer_id' => $data['customer_id'],
            ]);
            $cash_account_id = $data['cash_account_id'];
            $data['date'] = now();
            $data['created_by'] = $userId;
            $transaction = (new StoreTransactionLedger())->createTransaction($data);
            // $depositTransaction(new PurchaseOrderTransaction())->createTransaction($po, $morphMapName, $cash_account_id); #create transaction
            $debitLedger = (new StoreTransactionLedger())->storeLedger([
                'date' => now(),
                'value' => $data['deposit'],
                'personable_id' => $data['customer_id'],
                'personable_type' => 'customer',
                'transaction_id' => $transaction->id,
                'account_id' => $cash_account_id,
                'action' => 'debit',
            ]);
            #store credit ledger 
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'date' => now(),
                'value' => $data['deposit'],
                'personable_id' => $data['customer_id'],
                'personable_type' => 'customer',
                'transaction_id' => $transaction->id,
                'account_id' => $data['account_id'],
                'action' => 'credit',
            ]);
            return $customerDeposit;
        }
    }

    public function storeInvoiceCustomerDeposit($data, $userId)
    {
        // $isUsedDeposit = (bool) $data['is_used_deposit'];
        $depositBalance = $data['deposit_balance'];
        if ($depositBalance > 0) {
            if ($depositBalance >= $data['amount']) {
                $amount = $data['amount'];
            } elseif ($depositBalance < $data['amount']) {
                $amount = $depositBalance;
            }

            $customerDeposit = CustomerDeposit::create([
                'type' => 'withdrawal',
                'date_time' => now(),
                'amount' => $amount,
                'account_id' => $data['account_id'],
                // 'cash_account_id' => $data['cash_account_id'],
                'customer_id' => $data['customer_id'],
            ]);
            $data['date'] = now();
            $data['created_by'] = $userId;
            $transaction = (new StoreTransactionLedger())->createTransaction($data);
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'date' => now(),
                'value' => $amount,
                'personable_id' => $data['customer_id'],
                'personable_type' => 'customer',
                'transaction_id' => $transaction->id,
                'account_id' => $data['account_id'],
                'action' => 'credit',
            ]);
            return $customerDeposit;
        }

    }

    public function storeInvoiceTransaction($data)
    {

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
            if ($invoice->entity_id != null) {
                $updatedInvoice = $this->changeTable($invoice, $data['entity_id']);
                DB::commit();
                ResponseData($updatedInvoice, 200);
            }
            $newEntity = Entity::find($data['entity_id']);
            if ($newEntity->is_active) {
                ResponseMessage('Room is invalid', 422);
            }
            if ($invoice->invoice_type == 'endless_time') {
                return $this->invoiceService->changeRoomForEndlessTime($invoice, $newEntity);
            }

            $roomSessions = RoomSession::where('invoice_id', $invoice->id)->get();
            $firstRoomSession = $roomSessions->first();
            $latestRoomSession = $roomSessions->last();
            $current_time = Carbon::now();
            $entitySession = EntitySession::where('entity_id', $latestRoomSession->entitySession->entity_id)
                ->whereTime('start_time', '<=', $current_time)
                ->whereTime('end_time', '>=', $current_time)
                ->first();
            //added ksk
            $newEntitySession = EntitySession::where('entity_id', $data['entity_id'])
                ->whereTime('start_time', '<=', $current_time)
                ->whereTime('end_time', '>=', $current_time)
                ->first();
            //end 
            $previousEntity = Entity::find($entitySession->entity_id);
            $endTime = Carbon::parse($latestRoomSession->end_date);
            $sessionStartTime = Carbon::parse($firstRoomSession->start_date);
            $useHours = $sessionStartTime->diffInHours(Carbon::now());
            $totalDuration = $roomSessions->sum('session_duration');
            $leftDuration = $totalDuration - $useHours;
            $remainingEntitySessions = EntitySession::where('id', '>=', $newEntitySession->id)
                ->where('entity_id', $data['entity_id'])
                // whereIn('id', $roomSessions->pluck('entity_session_id'))
                // ->where('is_active', 1)
                ->get();
            // dd($remainingEntitySessions);
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

    public function changeTable($invoice, $newEntityId)
    {

        Entity::where('id', $newEntityId)->update([
            'status' => 'active',
            'is_active' => 1,
        ]);
        Entity::where('id', $invoice->entity_id)->update([
            'status' => 'inactive',
            'is_active' => 0,
        ]);

        $invoice->entity_id = $newEntityId;
        $invoice->save();
        return $invoice;
    }



    public function doneRoom(array $data)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($data['invoice_id']);
            if ($invoice->entity_id != null) {
                $tableResponseData = $this->doneInvoiceForTable($invoice);
                DB::commit();
                ResponseData($tableResponseData);
            }
            $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
            $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->with(['entitySession.entity'])->get();
            $total_duration = 0;
            $total_session_value = 0;
            $total_service_value = 0;
            $total_accessory_value = 0;
            $invoiceServices = $invoice->invoiceService;
            if ($invoice->order) {
                $this->invoiceService->checkOrderStatus($invoice->order->orderItems);
            }
            $invoiceAccessories = $invoice->accessories;
            $roomDoneResponse['is_service'] = 1;
            if ($invoiceServices->isEmpty()) {
                $roomDoneResponse['is_service'] = 0;
            }
            foreach ($invoiceServices as $invoiceService) {
                $time = $invoiceService->end_date != null ? $invoiceService->end_date : now();
                $serviceValue = $this->invoiceService->getServiceValue($invoiceService, $time);
                $total_service_value += $serviceValue;
            }
            foreach ($roomSessions as $room) {
                $total_session_value += $room->price;
                $total_duration += $room->session_duration ?? 0;
            }

            foreach ($invoiceAccessories as $invoiceAccessorie) {
                $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price * $invoiceAccessorie->quantity;
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
            $roomDoneResponse['total_service_value'] = $total_service_value;
            $roomDoneResponse['total_accessory_value'] = $total_accessory_value;
            DB::commit();
            ResponseData($roomDoneResponse);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function doneInvoiceForTable($invoice)
    {
        if (!$invoice) {
            ResponseMessage('Invoice is invalid', 422);
        }
        $this->invoiceService->checkOrderStatus($invoice->order->orderItems);
        $customerTotal = $this->getCustomerTotal($invoice->customer_id);
        $total = 0;
        $totalDiscount = 0;
        $invoiceServiceCollection = collect();
        $total_service_value = 0;
        $total_accessory_value = 0;
        $invoiceServices = $invoice->invoiceService;
        foreach ($invoiceServices as $invoiceService) {
            $time = $invoiceService->end_date != null ? $invoiceService->end_date : now();
            $this->invoiceService->calculateInvoiceService($invoiceService, $time);
            $total_service_value += $invoiceService->service_value;
        }
        $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
        // end service
        // invoice accessory
        $invoiceAccessories = $invoice->accessories;
        foreach ($invoiceAccessories as $invoiceAccessorie) {
            $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price * $invoiceAccessorie->quantity;
        }
        foreach ($invoice->orders as $order) {
            if (existOrderItemByStatus($order->orderItems, 'not_yet')) {
                ResponseMessage('Some items still cooking', 419);
            }
            foreach ($order->orderItems as $orderItem) {
                // if ($orderItem->status == 'done') {
                $total += $orderItem->price;
                $totalDiscount += $orderItem->discount_value;
                // }
            }
            // $total += $order->total;
            // $totalDiscount += $order->total_discount_price;
        }

        $responseData = [];
        $responseData = $this->getCustomerLevel($customerTotal, $responseData);
        $responseData['invoice_id'] = $invoice->id;
        $responseData['entity_id'] = $invoice->entity_id;
        $responseData['food_discount'] = $totalDiscount;
        $responseData['total'] = $total;
        $responseData['customer_total'] = $customerTotal;
        $responseData['total_service_value'] = $total_service_value;
        $responseData['total_accessory_value'] = $total_accessory_value;
        return $responseData;
        // dd($totalDiscount);
    }

    public function doneEntityWithInvoice(array $data)
    {
        //payload
        //invoice_id: 36
// discount_type: null
// order_categories: []
// total: 10000
// order_discount: 0
// discount_total: 0
// end_date: null 
        //end
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($data['invoice_id']);
            if (!$invoice) {
                ResponseMessage('Invoice not found', 404);
            }
            //added end_invoice for table oct 25 2024
            if ($invoice && $invoice->entity_id != null) {
                $invoice = $this->doneTableForInvoice($data, $invoice);
                $this->invoiceService->updateEntityStatus($invoice->entity_id, 'inactive'); // after done invoice ,update entity staus to inactive
                $this->addTargetPosition($invoice->id, $invoice->created_by, $data['total']); //add sale target position for related role
                $this->addTargetMenu($invoice->id); //add sale target position for related role
                $this->broadcastNotification($invoice->entity_id); //send notifcation;
                DB::commit();
                return $invoice;
            }
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
            //temp command
            // if ($order) {
            //     $allSold = $order->orderItems->every(function ($item) {
            //         return $item->status === 'done';
            //     });
            //     if ($allSold == false) {
            //         ResponseMessage('Not all order items are done', 422);
            //     }
            // }
            //end temp
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
            $total_service_value = 0;
            $invoiceServices = $invoice->invoiceService;
            foreach ($invoiceServices as $invoiceService) {
                $serviceValue = $this->invoiceService->getServiceValue($invoiceService, $data['end_date']);
                if ($invoiceService->is_active == 1) {
                    $invoiceService->end_date = $data['end_date'];
                    $invoiceService->service_value = $serviceValue;
                    $invoiceService->is_active = 0;
                    $invoiceService->save();
                }
                $total_service_value += $serviceValue;
            }

            $data['discount_value'] = $room_discount_value + $bdDiscount + $customerLevelDiscount + $discount_value;
            $data['discount_total'] = $room_discount_value + $bdDiscount + $customerLevelDiscount;
            $data['total'] -= $room_discount_value;
            $data['tax'] = $tax;
            $data['service_charge'] = $service_charge;
            $data['total_session_price'] = $total_session_price;
            $data['order_discount_value'] = $order_discount;
            $data['total_service_value'] = $total_service_value;
            $data['sub_total'] = ($data['total']) - ($tax + $service_charge);
            $data['payment_status'] = 'received';
            $data['complete_date'] = CurrentTime();
            $data['invoice_id'] = $invoice_id;
            $invoice->update($data);
            //update is active  to room_session 
            $this->invoiceService->updateIsActive($invoice->id, 0);
            //customer deposit 
            $customerDepositData['customer_id'] = $invoice->customer_id;
            $customerDepositData['account_id'] = $invoice->customer->account_id;
            $customerDepositData['amount'] = $data['total'];
            $customerDepositData['deposit_balance'] = $this->getCustomerDepositBalance($customer->id);
            //end
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
            //store customer deposit
            $this->storeInvoiceCustomerDeposit($customerDepositData, UserData()->id);
            //store invoice transaction

            $this->ledgerAndTransactionForInvoice([
                'payment_type' => 'cash',
                'invoice_id' => $invoice->id,
                // 'food_charge' => $foodCharge,
                // 'beverage_charge' => $beverageCharge,
                'total_session_price' => $total_session_price,
                'service_charge' => $service_charge,
                'tax' => $tax,
                'discount_total' => $data['discount_total'],
            ]);

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

    public function doneTableForInvoice($data, $invoice)
    {
        $total_service_value = 0;
        $invoiceServices = $invoice->invoiceService;
        foreach ($invoiceServices as $invoiceService) {
            $serviceValue = $this->invoiceService->getServiceValue($invoiceService, $data['end_date']);
            if ($invoiceService->is_active == 1) {
                $invoiceService->end_date = $data['end_date'];
                $invoiceService->service_value = $serviceValue;
                $invoiceService->is_active = 0;
                $invoiceService->save();
            }
            $total_service_value += $serviceValue;
        }
        if (isset($data['discount_type'])) {
            $data['discount_value'] = $data['discount_type'] == null || $data['discount_type'] == "null" ? 0 : $data['discount_value'];
        }
        $data['total_discount'] = $data['birthday_discount'] + $data['customer_level_discount'] + $data['discount_value'] + $data['order_discount'];
        $data['sub_total'] = ($data['total'] + $data['tax'] + $data['service_charge']) - $data['total_discount'];
        $data['total'] = $data['total'] + $data['total_discount'];
        $data['payment_status'] = 'received';
        $data['complete_date'] = CurrentTime();
        $invoice->update($data);
        return $invoice;
    }

    public function addTargetPosition($invoiceId, $staffId, $total)
    {
        $soldStaff = Staff::find($staffId);
        if (!$soldStaff) {
            ResponseMessage('Staff not found', 404);
        }
        $firstRole = $soldStaff->roles->first();
        if (!$firstRole) {
            ResponseMessage('Role not found', 404);
        }
        TargetPositionResult::create([
            'role_id' => $firstRole->id,
            'date_time' => CurrentTime(),
            'invoice_id' => $invoiceId,
            'amount' => $total,
        ]);
    }

    public function addTargetMenu($invoiceId)
    {
        $order = Order::where('invoice_id', $invoiceId)->first();
        if ($order) {
            $orderItems = $order->orderItems;
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
                    'invoice_id' => $invoiceId,
                    'area_id' => $orderItem['area_id'],
                    'menu_id' => $orderItem['menu_id'],
                    'quantity' => $orderItem['quantity'],
                ]);
            }
        }
    }
    public function broadcastNotification($entityId)
    {
        $entity = Entity::find($entityId);
        if (!$entity) {
            ResponseMessage('Entity No found', 404);
        }
        $catering_department = Department::where('name', 'Catering')->first();
        if (!$catering_department) {
            ResponseMessage('Catering department not found', 404);
        }
        $msg = "The {$entity->name} is now closed. Thank you.";
        //i think this role is not reliable to send notification
        $role = Role::where('name', 'Staff')->where('department_id', $catering_department->id)->first();
        if (!$role) {
            ResponseMessage('Role not found', 404);
        }
        broadcast(new RoomDoneNotificationRequest($entity, $msg, $role->id));
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
        $posBook = $data['payment_type'] == 'cash' ? $this->invoiceService->accountByCode('2-1011') : $this->invoiceService->accountByCode('2-1012');

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

        if (isset($data['food_charge']) && $data['food_charge'] != 0) {
            $foodKtvAcc = $this->invoiceService->accountByCode('5-0101')->first();
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

        if (isset($data['beverage_charge']) && $data['beverage_charge'] != 0) {

            $beverageKtvAcc = $this->invoiceService->accountByCode('5-0102')->first();

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
            $ktvRoomAcc = $this->invoiceService->accountByCode('5-0103');
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
            $serviceMoneyAcc = $this->invoiceService->accountByCode('6-2009');

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
            $taxAcc = $this->invoiceService->accountByCode('6-9002');
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
            $discountAcc = $this->invoiceService->accountByCode('6-2003');

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


    //add service 
    public function addService($request)
    {

        DB::beginTransaction();
        try {
            $existingService = InvoiceService::where('service_id', $request->service_id)
                ->where('invoice_id', $request->invoice_id)
                ->first();
            if (!$existingService) {
                $createdService = InvoiceService::create([
                    'start_date' => $request->start_date,
                    'invoice_id' => $request->invoice_id,
                    'service_id' => $request->service_id,
                    'is_active' => 1,
                ]);
                DB::commit();
                ResponseMessage('Invoice Service Create Successfully', 200);
                // Optionally, you can return or do something with the created service
            } else {
                ResponseMessage('InvoiceService already exists.', 409);
                // If it exists, you can return a response or handle accordingly
            }
            ResponseMessage('Service Added Succesfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function endService($request)
    {
        DB::beginTransaction();
        try {
            $existingService = InvoiceService::where('id', $request->invoice_service_id)
                ->whereNull('end_date')
                ->first();
            if ($existingService) {
                $this->invoiceService->calculateInvoiceService($existingService, $request->end_date);
                $updatedInvoiceService = InvoiceService::where('id', $request->invoice_service_id)
                    ->update([
                        'end_date' => $request->end_date,
                        'service_value' => $existingService->service_value,
                        'is_active' => 0,
                    ]);
                // dd($existingService->service_value);
                DB::commit();
                ResponseMessage('InvoiceService End successfully', 200);
                // return $invoiceService;
            } else {
                ResponseMessage('InvoiceService already ended', 200);
            }
            // If it doesn't exist, create a new InvoiceService

        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
