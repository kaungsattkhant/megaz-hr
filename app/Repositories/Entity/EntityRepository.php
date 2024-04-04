<?php

namespace App\Repositories\Entity;

use App\Models\Area;
use App\Models\Entity;
use Illuminate\Http\Request;

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
                ->paginate(config('common.list_count'));
        } else {
            $entities = Entity::where('is_available', 1)
                ->whereEntityType($type)
                ->with('service_category')
                ->get();
            return $entities;
        }
    }

    public function entityWithInvoice(array $data)
    {
        $area = Area::find($data['area_id']);
        $area = Area::where('name', 'KTV Rooms')->first();  //don't need get()
        $currentDate = $data['current_date'];
        if(isset($data['type']))
        {
            $entities = Entity::where('area_id', $area->id)->where("entity_type", $data['type'])->where("is_available", 1)->with(["invoices" => function ($query) use ($currentDate) {
                $query->where("complete_date", null)
                    ->whereBetween("invoice_date", [$currentDate . " 00:00:00", $currentDate . " 23:59:59"])
                    ->select("id", "invoice_id", "entity_id")->with("sessions");
            }])->get();
        }else{
            $entities = Entity::where('area_id', $area->id)->where("is_available", 1)->with(["invoices" => function ($query) use ($currentDate) {
                $query->where("complete_date", null)
                    ->whereBetween("invoice_date", [$currentDate . " 00:00:00", $currentDate . " 23:59:59"])
                    ->select("id", "invoice_id", "entity_id")->with("sessions");
            }])->get();
        }
        return $entities;
    }


    public function entityDetail(array $data, int $entityId)
    {
        $currentDate = CurrentDate();
        if (isset($data['current_date'])) {
            $currentDate = $data['current_date'];
        }

        $entity = Entity::with(["invoices" => function ($query) use ($currentDate) {
            $query->where("complete_date", null)
                ->whereBetween("invoice_date", [$currentDate . " 00:00:00", $currentDate . " 23:59:59"])
                ->select("id", "invoice_id", "entity_id")->with(["sessions", "orders.orderItems.menu"]);
        }])->find($entityId);

        return $entity;
    }

    public function createData(array $data)
    {
        $entity = Entity::create($data);
        return $entity;
    }

    public function updateData(array $data, int $id)
    {
        $service = Entity::find($id);
        if ($service) {
            $data = RemoveNullValues($data);
            $service->update($data);
        }
        return $service;
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

    public function inactiveRoomsList(Request $request)
    {
        $entities = Entity::where("entity_type", "room")
            ->where("is_active", 0)->get();

        return $entities;
    }
}
