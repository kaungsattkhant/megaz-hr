<?php

namespace App\Repositories\Entity;

use App\Models\Area;
use App\Models\Entity;
use App\Models\EntitySession;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityRepository implements EntityRepositoryInterface
{
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

        $entities = Entity::where('is_available', 1)
            ->where('area_id', $area->id)
            ->with('entitySessions.roomSession.invoice')
            ->get();

        return $entities;
    }


    public function entityDetail(array $data, int $entitySessionId)
    {
        $entitySession = EntitySession::where('is_available', 1)
            ->with(['roomSessions' => function ($query) {
                $query->latest()->first();
            }])
            ->find($entitySessionId);


        foreach ($entitySession->roomSessions as $roomSession) {
            $invoice = $roomSession->invoice;
            $invoice->package;
            if ($invoice) {
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
        }

        return $entitySession;
    }


    public function entitySessionWithInvoice(array $data, int $entityId)
    {
        $entity = Entity::find($entityId);

        $current_time = Carbon::now()->format('H:i');
        $entity = EntitySession::where('is_available', 1)
            ->where('entity_id', $entity->id)
            ->whereTime('start_time', '<=', $current_time) // Filter by current time within start and end
            ->whereTime('end_time', '>=', $current_time)
            ->with(['entity','roomSessions' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1); // Get the most recent room session
            }])
            ->first();


        $entityStartTime = EntitySession::where('is_available',1)->where('entity_id',$entity->id)->where('is_active',1)->get();
        $entityEndTime = EntitySession::where('is_available',1)->where('entity_id',$entity->id)->where('is_active',1)->get();

        foreach ($entity->roomSessions as $roomSession) {
            $invoice = $roomSession->invoice; // Access the invoice for the current room session
            $invoice->package;
            if ($invoice) { // Check if there is an associated invoice
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
        }

        return $entity;
    }



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
        $area = Area::find($data['area_id']);
        if (isset($data['type'])) {
            $entities = Entity::where("entity_type", $data['type'])->where('area_id', $area->id)->where("is_active", 0)->get();
        } else {
            $entities = Entity::where("is_active", 0)->where('area_id', $area->id)->get();
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
