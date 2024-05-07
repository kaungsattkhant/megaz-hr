<?php

namespace App\Repositories\Uom;

use App\Models\Uom;
use App\Models\UomConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UomRepository implements UomRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Uom::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $uoms = Uom::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'uoms');
            $paginationData['uoms'] = $uoms;

            return $paginationData;
        } else {
            $uoms = Uom::all();
            return $uoms;
        }
    }

    public function uomConversaionList(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = UomConversion::with('baseUnit', 'conversionUnit')->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $uoms = UomConversion::with('baseUnit', 'conversionUnit')->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'uoms');
            $paginationData['uoms'] = $uoms;

            return $paginationData;
        } else {
            $uoms = UomConversion::with('baseUnit', 'conversionUnit')->get();
            return $uoms;
        }
    }

    public function createUomConversion(array $data)
    {
        DB::beginTransaction();
        try {
            $baseUnitUom = $this->createUom($data['base_unit_name']);
            $conversionUom = $this->createUom($data['conversion_unit_name']);

            $data['base_unit_id'] = $baseUnitUom->id;
            $data['conversion_unit_id'] = $conversionUom->id;

            $data['converstion'] = $data['conversion'];
            $data['created_by'] = UserData()->id;
            $uomConversion = UomConversion::create($data);
            DB::commit();
            $uomConversion = UomConversion::with('baseUnit', 'conversionUnit')->find($uomConversion->id);
            return $uomConversion;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createUom(string $name)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $data['name'] = $name;
            $uom = Uom::create($data);
            DB::commit();
            return $uom;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateUomConversion($data)
    {
        DB::beginTransaction();
        try {
            $baseUnitUom = $this->updateData($data['base_unit_name'], $data['base_unit_id']);
            $conversionUom = $this->updateData($data['conversion_unit_name'], $data['conversion_unit_id']);
            $uomConversion = UomConversion::where('base_unit_id', $data['base_unit_id'])->where('conversion_unit_id', $data['conversion_unit_id'])->first();
            $uomConversion->update($data);
            DB::commit();
            return $uomConversion;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMEssage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(string $name, int $id)
    {
        DB::beginTransaction();
        try {
            $uom = Uom::find($id);
            if ($uom) {
                $uom->name = $name;
                $uom->save();
                DB::commit();
            }
            return $uom;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        $uom = Uom::find($id);
        if ($uom) {
            $uom->is_active = 0;
            $uom->save();
            return true;
        } else {
            return false;
        }
    }
}
