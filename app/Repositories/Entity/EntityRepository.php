<?php

namespace App\Repositories\Entity;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\RoomSession;
use Illuminate\Http\Request;
use App\Models\EntitySession;
use App\Traits\CustomerTrait;
use App\Models\InvoiceService;
use App\Models\InvoiceSession;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerLevelDiscount;
use App\Services\InvoiceModelService;

class EntityRepository implements EntityRepositoryInterface
{

    use CustomerTrait;
    private $invoiceModelService;
    public function __construct(InvoiceModelService $invoiceModelService)
    {
        $this->invoiceModelService = $invoiceModelService;
    }
    public function listAllData(Request $request, string $entityType)
    {
        $type = $request->type;
        if ($request->per_page || $request->page) {
            return Entity::orderByDesc('id')
                ->with('service_category')
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->whereEntityType($type)
                ->orderBy('created_at', 'desc')
                ->paginate(config('common.list_count'));
        } else {
            $entities = Entity::where('is_available', 1)
                ->whereEntityType($type)
                ->with('service_category')
                ->orderBy('created_at', 'desc')
                ->get();
            return $entities;
        }
    }

    public function entityWithInvoice(array $data)
    {
        $area = Area::find($data['area_id']);

        $today = Carbon::today()->format('Y-m-d');
        $nextDay = Carbon::tomorrow()->format('Y-m-d');

        // $entities = Entity::where('is_available', 1)
        //     ->where('area_id', $area->id)
        //     ->with([
        //         'entitySessions' => function ($query) {
        //             $query->where('start_time', '>=', '10:01:00')
        //                 ->orWhereBetween('start_time', ['00:01:00', '05:01:00'])
        //                 ->orderByRaw("CASE WHEN start_time >= '10:01:00' THEN 1 ELSE 2 END")
        //                 ->orderBy('start_time');
        //         },
        //         'entitySessions.roomSession.invoice'
        //     ])
        //     ->get();
        $entities = Entity::where('is_available', 1)
            ->where('area_id', $area->id)
            ->with([
                'entitySessions' => function ($query) {
                    $query->where('start_time', '>=', '09:01:00')
                        ->orWhereBetween('start_time', ['00:01:00', '05:01:00'])
                        ->orderByRaw("CASE WHEN start_time >= '09:01:00' THEN 1 ELSE 2 END")
                        ->orderBy('start_time')
                        ->selectRaw('*, 
                    CASE 
                        WHEN start_time >= "10:01:00" THEN CONCAT(CURRENT_DATE, " ", start_time) 
                        ELSE CONCAT(DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), " ", start_time)
                    END AS start_date_time,
                    CASE 
                        WHEN end_time >= "10:01:00" THEN CONCAT(CURRENT_DATE, " ", end_time) 
                        ELSE CONCAT(DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), " ", end_time)
                    END AS end_date_time');
                },
                'entitySessions.roomSession.invoice'
            ])
            ->get();

        foreach ($entities as $entity) {
            if ($entity->entity_type == 'room' && $entity->is_active == 1) {
                $invoiceSession = InvoiceSession::where('is_active', 1)->where('entity_id', $entity->id)->first();
                if ($invoiceSession) {
                    $entity->start_time = $invoiceSession->start_date_time;
                    $entity->end_time = $invoiceSession->end_date_time;
                    $entity->invoice=$invoiceSession;
                } else {
                    $entity->start_time = null;
                    $entity->end_time = null;
                    $entity->invoice=null;
                }
            }
            //     $roomSessions = collect();
            //     $startTimes = collect();
            //     $endTimes = collect();
            //     $entitySessions = $entity->entitySessions()
            //         ->where('is_active', 1)
            //         ->get();
            //     foreach ($entitySessions as $entitySession) {
            //         foreach ($entitySession->roomSessions()->where('is_active', 1)->get() as $roomSession) {
            //             // dd($roomSession->invoice->payment_status);
            //             $roomSessions->push($roomSession);
            //             $startTimes->push($roomSession->start_date);
            //             $endTimes->push($roomSession->end_date);
            //         }
            //     }
            //     $entity->start_time = CurrentTime();
            //     $entity->end_time = CurrentTime();
            //     $totalSessionDuration = (float) number_format($roomSessions->sum('session_duration'), 2);
            //     $startTimes = $startTimes->sortBy(function ($timestamp) {
            //         return strtotime($timestamp);
            //     })->values(); // Re-index the collection
            //     $endTimes = $endTimes->sortByDesc(function ($timestamp) {
            //         return strtotime($timestamp);
            //     })->values(); // Re-index the collection

            //     $firstStartTime = $startTimes->isNotEmpty() ? $startTimes[0] : null;

            //     $calculatedEndTime = Carbon::parse($firstStartTime)->addHours($totalSessionDuration)->format('Y-m-d H:i:s');
            //     $lastEndTime = $endTimes->isNotEmpty() ? $endTimes[0] : null;
            //     $entity->start_time = $firstStartTime;
            //     $entity->end_time = $calculatedEndTime;

            //     // dd($startTimes);
            // } else {
            //     $entity->start_time = null;
            //     $entity->end_time = null;
            // }

            // ResponseData($roomSessions);
        }

        return $entities;
    }

    public function entityDetail(array $data, int $entitySessionId)
    {
        $entitySession = EntitySession::where('is_available', 1)
            ->with([
                'entity'
            ])
            ->find($entitySessionId);
        if (!$entitySession) {
            ResponseMessage('Entity Session not found', 419);
        }
        $roomSession = RoomSession::whereHas('invoiceSession', function ($q) {
            $q->where('is_active', 1);
        })
            ->where('entity_session_id', $entitySessionId)
            ->first();
        //deposit customer 
        // $firstRoomSession = $entitySession->roomSessions->first();
        if (!$roomSession) {
            ResponseMessage('Invoice Room Session is invalid', 419);
        }
        if (!$roomSession->invoiceSession) {
            ResponseMessage('Invoice Session is invalid', 419);
        }
        $invoice = $roomSession->invoiceSession->invoice;
        $entityDetail = $this->entityDetailByEntityId($invoice, $entitySession->entity_id, $roomSession->invoiceSession->start_date_time, $roomSession->invoiceSession->end_date_time);
        return $entityDetail;

        // $invoiceServiceCollection = collect();
        // $invoiceAccessoryCollection = collect();
        // $total_service_value = $total_accessory_value = 0;
        // $roomSession = RoomSession::whereHas('invoiceSession', function ($q) {
        //     $q->where('is_active', 1);
        // })
        //     ->where('entity_session_id', $entitySessionId)
        //     ->first();
        // //deposit customer 
        // // $firstRoomSession = $entitySession->roomSessions->first();
        // if (!$roomSession) {
        //     ResponseMessage('Invoice Room Session is invalid', 419);
        // }
        // $customer = $roomSession->invoiceSession->invoice->customer;
        // $customerDepositBalance = $this->getCustomerDepositBalance($customer->id);
        // //end deposit
        // $invoice = $roomSession->invoiceSession->invoice;
        // $invoice->package;
        // if ($invoice) {
        //     //service
        //     $invoiceServices = $invoice->invoiceService;
        //     foreach ($invoiceServices as $invoiceService) {
        //         $time = $invoiceService->end_date != null ? $invoiceService->end_date : now();
        //         $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
        //         $total_service_value += $invoiceService->service_value;
        //     }
        //     // $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
        //     //serice

        //     //accesory
        //     $invoiceAccessories = $invoice->accessories;
        //     foreach ($invoiceAccessories as $invoiceAccessorie) {
        //         $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price * $invoiceAccessorie->quantity;
        //     }
        //     // $invoiceAccessoryCollection = $invoiceAccessoryCollection->merge($invoice->invoiceAccessories);
        //     //end_accessoryI
        //     $consolidatedOrderItems = [];
        //     foreach ($invoice->orders as $order) {
        //         $orderItems = OrderItem::where('order_id', $order->id)->get();

        //         foreach ($orderItems as $orderItem) {
        //             $menuId = $orderItem->menu_id;
        //             $status = $orderItem->status;

        //             if (isset($consolidatedOrderItems[$menuId][$status])) {
        //                 $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
        //                 $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
        //                 $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_price;
        //             } else {
        //                 $consolidatedOrderItems[$menuId][$status] = $orderItem;
        //             }
        //         }
        //     }
        //     foreach ($invoice->orders as $order) {
        //         $order->order_items = collect();

        //         foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
        //             foreach ($itemsByStatus as $status => $order_items) {
        //                 $order_items->menu;
        //                 $order->order_items->push($order_items);
        //             }
        //         }
        //     }
        //     unset($invoice['accessories']);
        //     unset($invoice['invoice_service']);
        //     unset($invoice['total_accessory_value']);
        //     unset($invoice['total_service_value']);
        //     // unset($invoice,'invoice.accessories');
        //     //service list
        // }
        // $order = $invoice->order;
        // $total_order_discount_price = 0;
        // if ($order) {
        //     $total_order_discount_price = $order->total_discount_price;
        // }
        // // }
        // $entitySession->start_date_time = $roomSession->invoiceSession->start_date_time;
        // $entitySession->end_date_time = $roomSession->invoiceSession->end_date_time;
        // $entitySession->services = $invoiceServices;
        // // $entitySession->total_order_discount_value = $invoice->order->total_discount_price;
        // $entitySession->invoice_accessories = $invoiceAccessories;
        // $entitySession->total_service_value = $total_service_value;
        // $entitySession->total_accessory_value = $total_accessory_value;
        // $entitySession->total_order_discount_price = $total_order_discount_price;
        // $entitySession->deposit_balance = $customerDepositBalance;
        // $entitySession->customer_id = $customer->id;
        // $entitySession->account_id = $customer->account_id;
        // $entitySession->total_session_price = $invoice->total_session_price;
        // $entitySession->invoice = $invoice;
        // return $entitySession;
    }

    public function entitySessionWithInvoice(array $data, int $entityId)
    {
        $entity = Entity::find($entityId);
        if ($entity->entity_type == 'room') {

            $activeInvoiceSession = InvoiceSession::where('is_active', 1)->where('entity_id', $entityId)->first();
            if (!$activeInvoiceSession) {
                ResponseMessage('Active Invoice Not Found', 419);
            }
            $invoice = $activeInvoiceSession->invoice;
            if (!$invoice) {
                ResponseMessage('Active Invoice Not Found', 419);
            }
            $entityDetail = $this->entityDetailByEntityId($invoice, $entityId, $activeInvoiceSession->start_date_time, $activeInvoiceSession->end_date_time);
            return $entityDetail;

            // $invoiceServiceCollection = collect();
            // $total_service_value = 0;
            // $total_accessory_value = 0;

            // // foreach ($roomSessions as $roomSession) {don't need
            // // $invoice = $roomSession->invoice; // 'don't need'
            // $invoice->package;
            // // if ($invoice) {
            // //service
            // // $entitySession['invoice']=$invoice;
            // $invoiceServices = $invoice->invoiceService;
            // foreach ($invoiceServices as $invoiceService) {
            //     $time = $invoiceService->end_date != null ? $invoiceService->end_date : now();
            //     $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
            //     $total_service_value += $invoiceService->service_value;
            // }
            // $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
            // // end service
            // // invoice accessory
            // $invoiceAccessories = $invoice->accessories;
            // foreach ($invoiceAccessories as $invoiceAccessorie) {
            //     $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price * $invoiceAccessorie->quantity;
            // }
            // $consolidatedOrderItems = [];
            // foreach ($invoice->orders as $order) {
            //     $orderItems = OrderItem::where('order_id', $order->id)->get();
            //     foreach ($orderItems as $orderItem) {
            //         $menuId = $orderItem->menu_id;
            //         $status = $orderItem->status;
            //         if (isset($consolidatedOrderItems[$menuId][$status])) {
            //             $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
            //             $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
            //             $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_price;
            //         } else {
            //             $consolidatedOrderItems[$menuId][$status] = $orderItem;
            //         }
            //     }
            // }

            // foreach ($invoice->orders as $order) {
            //     $order->order_items = collect();

            //     foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
            //         foreach ($itemsByStatus as $status => $order_items) {
            //             $order_items->menu;
            //             $order->order_items->push($order_items);
            //         }
            //     }
            // }
            // unset($invoice['accessories']);
            // unset($invoice['invoice_service']);
            // unset($invoice['total_accessory_value']);
            // unset($invoice['total_service_value']);
            // // }
            // $order = $invoice->order;
            // $total_order_discount_price = 0;
            // if ($order) {
            //     $total_order_discount_price = $order->total_discount_price;
            // }
            // unset($invoice['order']);
            // // }
            // // $entitySession['start_date'] = $firstRoomSession->start_date;
            // // $entitySession['end_date'] = $lastRoomSession->end_date;
            // $entitySession['start_date'] = $activeInvoiceSession->start_date_time;
            // $entitySession['end_date'] = $activeInvoiceSession->end_date_time;
            // $entitySession['invoice'] = $invoice;
            // //service add response
            // $entitySession['services'] = $invoiceServiceCollection;
            // $entitySession['invoice_accessories'] = $invoiceAccessories;
            // $entitySession['total_service_value'] = $total_service_value;
            // $entitySession['total_accessory_value'] = $total_accessory_value;
            // $entitySession['total_order_discount_price'] = $total_order_discount_price;
            // //service add response
            // return $entitySession;
        }

        if ($entity->entity_type == 'table') {
            if (!$entity->latestInvoice) {
                ResponseMessage('Invoice Detail is invalid', 419);
            }
            $invoice = $entity->latestInvoice;
            //Entity Detail changed 
            $entityDetail = $this->entityDetailByEntityId($invoice, $entityId);
            return $entityDetail;
            //entity detail

            $total_service_value = 0;
            $total_accessory_value = 0;
            $invoiceServiceCollection = collect();
            $invoiceServices = $invoice->invoiceService;
            foreach ($invoiceServices as $invoiceService) {
                $time = $invoiceService->end_date != null ? $invoiceService->end_date : now();
                $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
                $total_service_value += $invoiceService->service_value;
            }
            $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
            // end service
            // invoice accessory
            $invoiceAccessories = $invoice->accessories;
            foreach ($invoiceAccessories as $invoiceAccessorie) {
                $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price * $invoiceAccessorie->quantity;
            }
            $orders = $invoice->orders;

            if ($orders->isNotEmpty()) {
                // $entity->invoice = $orders;
                foreach ($orders as $order) {
                    $orderItems = OrderItem::where('order_id', $order->id)->get();
                    foreach ($orderItems as $orderItem) {
                        $menuId = $orderItem->menu_id;
                        $status = $orderItem->status;
                        if (isset($consolidatedOrderItems[$menuId][$status])) {
                            $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
                            $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
                            $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_value;
                        } else {
                            $consolidatedOrderItems[$menuId][$status] = $orderItem;
                        }
                    }
                }



                foreach ($orders as $order) {
                    $order->order_items = collect();

                    foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
                        foreach ($itemsByStatus as $status => $order_items) {
                            $order_items->menu;
                            $order->order_items->push($order_items);
                        }
                    }
                }
                $invoice->orders = $orders;
                $entity->invoice = $invoice;
            }
            $order = $invoice->order;
            $total_order_discount_price = 0;
            if ($order) {
                $total_order_discount_price = $order->total_discount_price;
            }
            unset($invoice['order']);
            $responseData = [];
            $responseData['entity_id'] = $entity->id;
            $responseData['room_sessions'] = $entity;
            $responseData['services'] = $invoiceServices;
            $responseData['invoice_accessories'] = $invoiceAccessories;
            $responseData['total_service_value'] = $total_service_value;
            $responseData['total_accessory_value'] = $total_accessory_value;
            $responseData['total_order_discount_price'] = $total_accessory_value;
            return $responseData;
        }
    }


    public function entityDetailByEntityId($invoice, $entityId, $startDateTime = null, $endDateTime = null)
    {
        $entity = Entity::find(id: $entityId);
        // if($entity->entity_type=='table'){
        //     return $this->tableWithInvoiceDetail([],$entityId);
        // }
        if (!$entity) {
            ResponseMessage('Entity is invalid');
        }
        $invoiceServiceCollection = collect();
        $invoiceAccessoryCollection = collect();
        $total_service_value = $total_accessory_value = 0;
        // $roomSession = RoomSession::whereHas('invoiceSession', function ($q) {
        //     $q->where('is_active', 1);
        // })
        //     ->where('entity_session_id', $entitySessionId)
        //     ->first();
        // if ($roomSession)
        //     //deposit customer 
        //     // $firstRoomSession = $entitySession->roomSessions->first();
        //     if (!$roomSession) {
        //         ResponseMessage('Invoice Room Session is invalid', 419);
        //     }
        $customer = $invoice->customer;
        $customerDepositBalance = $this->getCustomerDepositBalance($customer->id);
        //end deposit
        // $invoice = $roomSession->invoiceSession->invoice;
        $invoice->package;
        if ($invoice) {
            //service
            $invoiceServices = $invoice->invoiceService;
            // if ($invoice->order) {
            //     $this->invoiceModelService->checkOrderStatus($invoice->order->orderItems);
            // }
            $entity->is_service = 1;
            if ($invoiceServices->isEmpty()) {
                $entity->is_service = 0;
            }
            foreach ($invoiceServices as $invoiceService) {
                $time = $invoiceService->end_date != null ? $invoiceService->end_date : now();
                $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
                $total_service_value += $invoiceService->service_value;
            }
            // $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
            //serice

            //accesory
            $invoiceAccessories = $invoice->accessories;
            foreach ($invoiceAccessories as $invoiceAccessorie) {
                
                $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price * $invoiceAccessorie->quantity;
            }
            // $invoiceAccessoryCollection = $invoiceAccessoryCollection->merge($invoice->invoiceAccessories);
            //end_accessoryI

            //customer level , birthday discount
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
                // $roomDoneResponse['customer_level'] = $customerLevel->name;
                // $roomDoneResponse['customer_level_discount_value'] = $customerLevel->promotion_value;
                $entity->customer_level = $customerLevel->name;
                $entity->customer_level_discount_value = $customerLevel->promotion_value;
            } else {
                // $roomDoneResponse['customer_level'] = 'no customer level';
                $entity->customer_level = 'no customer level';
            }

            // $roomDoneResponse['customer_total'] = $customerTotal;
            if ($customer) {
                $birthdate = Carbon::parse($customer->birthdate);
                // $roomDoneResponse['is_birthday'] = $birthdate->isBirthday($today);
                $entity->is_birthday = $birthdate->isBirthday($today);

            } else {
                $entity->is_birthday = false;
                // $roomDoneResponse['is_birthday'] = false;
            }

            //end 


            $consolidatedOrderItems = [];
            $total = 0;
            $totalDiscount = 0;
            foreach ($invoice->orders as $order) {
                $orderItems = OrderItem::where('order_id', $order->id)->get();
                foreach ($order->orderItems as $orderItem) {
                    $orderItem->load('menu');
                    // if ($orderItem->status == 'done') {
                    if($invoice->invoice_type!='package'){
                        $total += $orderItem->price;
                        $totalDiscount += $orderItem->discount_value;
                    }
                   
                    // }
                }
                foreach ($orderItems as $orderItem) {
                    $menuId = $orderItem->menu_id;
                    $status = $orderItem->status;
                    if (isset($consolidatedOrderItems[$menuId][$status])) {
                        $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
                        $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
                        $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_price;
                    } else {
                        $consolidatedOrderItems[$menuId][$status] = $orderItem;
                    }
                }
            }
            foreach ($invoice->orders as $order) {
                $order->order_items = collect();

                foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
                    foreach ($itemsByStatus as $status => $order_items) {
                        $order_items->menu;
                        $order->order_items->push($order_items);
                    }
                }
            }
            unset($invoice['accessories']);
            unset($invoice['invoice_service']);
            unset($invoice['total_accessory_value']);
            unset($invoice['total_service_value']);
            // unset($invoice,'invoice.accessories');
            //service list
        }
        $order = $invoice->order;
        $total_order_discount_price = 0;
        $total_order_value = 0;
        if ($order) {
            $total_order_discount_price = $order->total_discount_price;
            $total_order_value = $order->total;
        }
        // }
        $entity->entity_id = $entity->id;
        $entity->invoice_id = $invoice->id;
        $entity->start_date_time = $startDateTime;
        $entity->end_date_time = $endDateTime;
        $entity->services = $invoiceServices;
        $entity->customer_total = $customerTotal;
        $entity->food_discount = $totalDiscount;
        $entity->total = $totalDiscount;
        $entity->invoice_accessories = $invoiceAccessories;
        $entity->total_service_value = $total_service_value;
        $entity->total_accessory_value = $total_accessory_value;
        $entity->total_order_discount_price = $total_order_discount_price;
        $entity->food_discount = $total_order_discount_price;
        $entity->invoice_total = $invoice->total;
        $entity->total_order_value = $total_order_value;
        $entity->deposit_balance = $customerDepositBalance;
        $entity->customer_id = $customer->id;
        $entity->account_id = $customer->account_id;
        $entity->total_session_price = $invoice->total_session_price;
        $entity->invoice = $invoice;
        // dd($invoiec)
        return $entity;
    }
    public function tableWithInvoiceDetail(array $data, int $entityId)
    {
        $entity = Entity::find($entityId);
        if (!$entity->latestInvoice) {
            ResponseMessage('Invoice Detail is invalid', 419);
        }
        $invoice = $entity->latestInvoice;
        $orders = $invoice->orders;
        if ($orders->isNotEmpty()) {
            // $entity->invoice = $orders;
            foreach ($orders as $order) {
                $orderItems = OrderItem::where('order_id', $order->id)->get();
                foreach ($orderItems as $orderItem) {
                    $menuId = $orderItem->menu_id;
                    $status = $orderItem->status;
                    if (isset($consolidatedOrderItems[$menuId][$status])) {
                        $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
                        $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
                        $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_price;
                    } else {
                        $consolidatedOrderItems[$menuId][$status] = $orderItem;
                    }
                }
            }


            foreach ($orders as $order) {
                $order->order_items = collect();

                foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
                    foreach ($itemsByStatus as $status => $order_items) {
                        $order_items->menu;
                        $order->order_items->push($order_items);
                    }
                }
            }
            $invoice->orders = $orders;
            $entity->invoice = $invoice;
        }
        $responseData = [];
        $responseData['entity_id'] = $entity->id;
        $responseData['room_sessions'] = $entity;
        return $responseData;
        // $entitySession = EntitySession::where('is_active', 1)
        //     ->with(['entity', 'roomSessions'])->where('entity_id', $entityId)->first();

        // $invoiceId = $entitySession->roomSession->invoice_id;
        // $roomSessions = RoomSession::where('invoice_id', $invoiceId)
        //     ->orderBy('created_at')
        //     ->get();

        // $firstRoomSession = $roomSessions->first();
        // $lastRoomSession = $roomSessions->last();


        // foreach ($entitySession->roomSessions as $roomSession) {
        //     $invoice = $roomSession->invoice;
        //     $invoice->package;
        //     if ($invoice) {
        //         $consolidatedOrderItems = [];
        //         foreach ($invoice->orders as $order) {
        //             $orderItems = OrderItem::where('order_id', $order->id)->get();
        //             foreach ($orderItems as $orderItem) {
        //                 $menuId = $orderItem->menu_id;
        //                 $status = $orderItem->status;
        //                 if (isset($consolidatedOrderItems[$menuId][$status])) {
        //                     $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
        //                     $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
        //                     $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_price;
        //                 } else {
        //                     $consolidatedOrderItems[$menuId][$status] = $orderItem;
        //                 }
        //             }
        //         }

        //         foreach ($invoice->orders as $order) {
        //             $order->order_items = collect();

        //             foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
        //                 foreach ($itemsByStatus as $status => $order_items) {
        //                     $order_items->menu;
        //                     $order->order_items->push($order_items);
        //                 }
        //             }
        //         }
        //     }
        // }
        // $entitySession['start_date'] = $firstRoomSession->start_date;
        // $entitySession['end_date'] = $lastRoomSession->end_date;
        // return $entitySession;
    }


    // public function entitySessionWithInvoice(array $data, int $entityId)
    // {
    //     $entity = Entity::find($entityId);

    //     $current_time = Carbon::now()->format('H:i');


    //     $entitySession = EntitySession::where('is_active',1)->where('entity_id',$entityId)->first();
    //     dd($entitySession->roomSession);
    //     $invoiceId = $entitySession->roomSession->invoice_id;

    //     $roomSessions = RoomSession::where('invoice_id',$invoiceId)
    //         ->orderBy('created_at')
    //         ->get();
    //     $firstRoomSession = $roomSessions->first();
    //     $lastRoomSession = $roomSessions->last();

    //     foreach ($entity->roomSessions as $roomSession) {
    //         $invoice = $roomSession->invoice; // Access the invoice for the current room session
    //         $invoice->package;
    //         if ($invoice) { // Check if there is an associated invoice
    //             $consolidatedOrderItems = [];

    //             foreach ($invoice->orders as $order) {
    //                 $orderItems = OrderItem::where('order_id', $order->id)->get();

    //                 foreach ($orderItems as $orderItem) {
    //                     $menuId = $orderItem->menu_id;
    //                     $status = $orderItem->status;

    //                     if (isset($consolidatedOrderItems[$menuId][$status])) {
    //                         $consolidatedOrderItems[$menuId][$status]->quantity += $orderItem->quantity;
    //                         $consolidatedOrderItems[$menuId][$status]->price += $orderItem->price;
    //                         $consolidatedOrderItems[$menuId][$status]->discount_price += $orderItem->discount_price;
    //                     } else {
    //                         $consolidatedOrderItems[$menuId][$status] = $orderItem;
    //                     }
    //                 }
    //             }

    //             foreach ($invoice->orders as $order) {
    //                 $order->order_items = collect();

    //                 foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
    //                     foreach ($itemsByStatus as $status => $order_items) {
    //                         $order_items->menu;
    //                         $order->order_items->push($order_items);
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     $entity['start_time'] = $firstRoomSession->start_date;
    //     $entity['end_time'] = $lastRoomSession->end_date;
    //     return $entity;
    // }



    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $entity = Entity::create($data);
            if ($data['entity_type'] == 'room') {
                for ($hour = 0; $hour < 24; $hour++) {
                    $startTime = Carbon::createFromTime($hour, 1)->format('H:i');
                    $endTime = Carbon::createFromTime(($hour + 1) % 24, 0)->format('H:i');
                    $spansMidnight = $startTime > $endTime ? 1 : 0;
                    $EntitySession = EntitySession::create([
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_available' => 1,
                        'is_active' => 0,
                        'spans_midnight' => $spansMidnight,
                        'entity_id' => $entity->id,
                    ]);

                }
            }

            DB::commit();
            return $entity;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $service = Entity::find($id);
            if ($service) {
                $data = RemoveNullValues($data);
                $service->update($data);
            }
            DB::commit();
            return $service;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        $service = Entity::find($id);
        if ($service) {
            $service->is_available = 0;
            $service->save();
            return true;
        }
        return false;
    }

    public function inactiveEntityList($data)
    {
        // mobile
// ${base_url}entities/change?waiter=1                                              
// ${base_url}areas/${widget.areaId}/entities?type=room'
//api/areas/${area_id}/inactive_entities?type=room
        // $data['type'] = 'room';
        $area = Area::find($data['area_id']);
        if (isset($data['type'])) {
            $entities = Entity::where("entity_type", $data['type'])
                ->when($data['type'] == 'room', function ($q) {
                    $q->whereHas('entitySessions'); // Only include entities with non-empty entitySessions
                    // $q->withCount('entitySessions'); // Adds entity_sessions_count attribute
    
                })
                ->where('area_id', $area->id)->where("is_active", 0)
                ->get();
        } else {
            $entities = Entity::where("is_active", 0)
                ->where('area_id', $area->id)
                ->get();
        }

        return $entities;
    }

    // user app

    public function roomListForUserApp()
    {
        $rooms = Entity::where('entity_type', 'room')->get();
        ResponseData($rooms);
    }
}
