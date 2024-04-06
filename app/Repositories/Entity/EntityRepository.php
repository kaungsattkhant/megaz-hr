<?php

namespace App\Repositories\Entity;

use App\Models\Area;
use App\Models\Entity;
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
        $currentDate = $data['current_date'];


            $entities = Entity::where('area_id', $area->id)->where("is_available", 1)->with(["invoices" => function ($query) use ($currentDate) {
                $query->where("complete_date", null)->select("id", "invoice_id", "entity_id","total_session_price")->with("sessions");
            }])->get();

        return $entities;
    }


    public function entityDetail(array $data, int $entityId)
    {
        $entity = Entity::with(["invoices" => function ($query) {
            $query->whereNull("complete_date")
                  ->select("id", "invoice_id", "entity_id", "total_session_price")
                  ->with(["sessions", "orders.orderItems.menu"])
                  ->latest()
                  ->limit(1); // Get only the latest invoice
        }])->find($entityId);

        return $entity;


    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $entity = Entity::create($data);
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
}
