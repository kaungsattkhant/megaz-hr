<?php

namespace App\Repositories\Entity;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\RoomSession;
use Illuminate\Http\Request;
use App\Models\EntitySession;
use App\Models\InvoiceService;
use Illuminate\Support\Facades\DB;
use App\Services\InvoiceModelService;

class EntityRepository implements EntityRepositoryInterface
{

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
                    $query->where('start_time', '>=', '10:01:00')
                        ->orWhereBetween('start_time', ['00:01:00', '05:01:00'])
                        ->orderByRaw("CASE WHEN start_time >= '10:01:00' THEN 1 ELSE 2 END")
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
                $roomSessions = collect();
                $startTimes = collect();
                $endTimes = collect();
                $entitySessions = $entity->entitySessions()->where('is_active', 1)->get();
                foreach ($entitySessions as $entitySession) {
                    foreach ($entitySession->roomSessions()->get() as $roomSession) {
                        $roomSessions->push($roomSession);
                        $startTimes->push($roomSession->start_date);
                        $endTimes->push($roomSession->end_date);
                    }
                }
                $entity->start_time = CurrentTime();
                $entity->end_time = CurrentTime();

                $totalSessionDuration = (float) number_format($roomSessions->sum('session_duration'), 2);
                $startTimes = $startTimes->sortBy(function ($timestamp) {
                    return strtotime($timestamp);
                })->values(); // Re-index the collection
                $endTimes = $endTimes->sortByDesc(function ($timestamp) {
                    return strtotime($timestamp);
                })->values(); // Re-index the collection

                $firstStartTime = $startTimes->isNotEmpty() ? $startTimes[0] : null;

                $calculatedEndTime = Carbon::parse($firstStartTime)->addHours($totalSessionDuration)->format('Y-m-d H:i:s');
                $lastEndTime = $endTimes->isNotEmpty() ? $endTimes[0] : null;
                $entity->start_time = $firstStartTime;
                $entity->end_time = $calculatedEndTime;

                // dd($startTimes);
            } else {
                $entity->start_time = null;
                $entity->end_time = null;
            }

            // ResponseData($roomSessions);
        }

        return $entities;
    }

    public function entityDetail(array $data, int $entitySessionId)
    {
        $entitySession = EntitySession::where('is_available', 1)
            ->with([
                'roomSessions' => function ($query) {
                    $query->latest()->first();
                }
                ,
                'entity'
            ])
            ->find($entitySessionId);
        $invoiceServiceCollection = collect();
        $invoiceAccessoryCollection = collect();
        $total_service_value = $total_accessory_value = 0;
        // dd($entitySession->roomSessions);
        foreach ($entitySession->roomSessions as $roomSession) {
            $invoice = $roomSession->invoice;
            $invoice->package;
            if ($invoice) {
                //service
                $invoiceServices = $invoice->invoiceService;
                foreach ($invoiceServices as $invoiceService) {
                    $time=$invoiceService->end_date!=null? $invoiceService->end_date : now();
                    $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
                    $total_service_value += $invoiceService->service_value;
                }
                $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
                //serice
                //accesory
                $invoiceAccessories = $invoice->accessories;
                foreach ($invoiceAccessories as $invoiceAccessorie) {
                    $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price*$invoiceAccessorie->quantity;
                }
                $invoiceAccessoryCollection = $invoiceAccessoryCollection->merge($invoice->invoiceAccessories);
                //end_accessoryI
                $consolidatedOrderItems = [];
                foreach ($invoice->orders as $order) {
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
                foreach ($invoice->orders as $order) {
                    $order->order_items = collect();

                    foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
                        foreach ($itemsByStatus as $status => $order_items) {
                            $order_items->menu;
                            $order->order_items->push($order_items);
                        }
                    }
                }
                //service list
            }
        }
        // dd($invoiceServiceCollection);
        $entitySession->services = $invoiceServiceCollection;
        $entitySession->invoice_accessories = $invoiceAccessories;
        $entitySession->total_service_value = $total_service_value;
        $entitySession->total_accessory_value = $total_accessory_value;
        return $entitySession;
    }


    public function entitySessionWithInvoice(array $data, int $entityId)
    {
        $entity = Entity::find($entityId);
        if ($entity->entity_type == 'room') {
            $entitySession = EntitySession::where('is_active', 1)
                ->with(['entity', 'roomSessions', 'roomSession.invoice'])->where('entity_id', $entityId)->first();
            if (!$entitySession) {
                ResponseMessage('Entity have no invic ', 404);
            }
            $invoiceId = $entitySession->roomSession->invoice_id;
            $invoice = Invoice::find($invoiceId);
            $roomSessions = RoomSession::where('invoice_id', $invoiceId)
                ->orderBy('created_at')
                ->get();
            $firstRoomSession = $roomSessions->first();
            $lastRoomSession = $roomSessions->last();

            $invoiceServiceCollection = collect();
            $total_service_value = 0;
            $total_accessory_value = 0;

            // foreach ($roomSessions as $roomSession) {don't need
            // $invoice = $roomSession->invoice; // 'don't need'
            $invoice->package;
            if ($invoice) {
                //service
                // $entitySession['invoice']=$invoice;
                $invoiceServices = $invoice->invoiceService;
                foreach ($invoiceServices as $invoiceService) {
                    $time=$invoiceService->end_date!=null? $invoiceService->end_date : now();
                    $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
                    $total_service_value += $invoiceService->service_value;
                }
                $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
                // end service
                // invoice accessory
                $invoiceAccessories = $invoice->accessories;
                foreach ($invoiceAccessories as $invoiceAccessorie) {
                    $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price*$invoiceAccessorie->quantity;
                }
                $consolidatedOrderItems = [];
                foreach ($invoice->orders as $order) {
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

                foreach ($invoice->orders as $order) {
                    $order->order_items = collect();

                    foreach ($consolidatedOrderItems as $menuId => $itemsByStatus) {
                        foreach ($itemsByStatus as $status => $order_items) {
                            $order_items->menu;
                            $order->order_items->push($order_items);
                        }
                    }
                }
            }
            // }
            $entitySession['start_date'] = $firstRoomSession->start_date;
            $entitySession['end_date'] = $lastRoomSession->end_date;
            $entitySession['invoice'] = $invoice;
            //service add response
            $entitySession['services'] = $invoiceServiceCollection;
            $entitySession['invoice_accessories'] = $invoiceAccessories;
            $entitySession['total_service_value'] = $total_service_value;
            $entitySession['total_accessory_value'] = $total_accessory_value;
            //service add response
            return $entitySession;
        }
        if ($entity->entity_type == 'table') {
            if (!$entity->latestInvoice) {
                ResponseMessage('Invoice Detail is invalid', 419);
            }
            $invoice = $entity->latestInvoice;
            $total_service_value = 0;
            $total_accessory_value = 0;
            $invoiceServiceCollection = collect();
            $invoiceServices = $invoice->invoiceService;
                foreach ($invoiceServices as $invoiceService) {
                    $time=$invoiceService->end_date!=null? $invoiceService->end_date : now();
                    $this->invoiceModelService->calculateInvoiceService($invoiceService, $time);
                    $total_service_value += $invoiceService->service_value;
                }
                $invoiceServiceCollection = $invoiceServiceCollection->merge($invoice->invoiceService);
                // end service
                // invoice accessory
                $invoiceAccessories = $invoice->accessories;
                foreach ($invoiceAccessories as $invoiceAccessorie) {
                    $total_accessory_value += $invoiceAccessorie->accessory->accessory_price->price*$invoiceAccessorie->quantity;
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
            $responseData['services'] = $invoiceServiceCollection;
            $responseData['invoice_accessories'] = $invoiceAccessories;
            $responseData['total_service_value'] = $total_service_value;
            $responseData['total_accessory_value'] = $total_accessory_value;
            return $responseData;
        }
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
                    EntitySession::create([
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_available' => 1,
                        'is_active' => 0,
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
