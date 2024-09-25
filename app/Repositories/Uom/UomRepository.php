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
            $uoms = Uom::orderBy('created_at','desc')->paginate(config('common.list_count'));
            return $uoms;
        } else {
            $uoms = Uom::all();
            return $uoms;
        }
    }

    public function createUom(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $data['name'] = $data['name'];
            $uom = Uom::firstOrCreate(['name' => $data['name']], $data);
            if ($uom) {
                UomConversion::firstOrCreate(
                    [
                        'base_unit_id' => $uom->id,
                        'conversion_unit_id' => $uom->id,
                        'conversion'=>1,
                    ], [
                        'base_unit_id' => $uom->id,
                        'conversion_unit_id' => $uom->id,
                        'conversion'=>1,
                        'created_by'=>UserData()->id,
                        'is_show'=>0
                    ]
                );
            }
            DB::commit();
            return $uom;
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
            $uom = Uom::find($id);
            if ($uom) {
                $uom->name = $data['name'];
                $uom->save();
                DB::commit();
                return $uom;
            } else {
                ResponseMessage("Uom not found", 404);
            }
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

    // uom conversion

    public function uomConversaionList(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = UomConversion::with('baseUnit', 'conversionUnit')->where('is_show',1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $uoms = UomConversion::with('baseUnit', 'conversionUnit')->skip($skip)->take($perPage)->where('is_show',1)->get();
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
            $data['created_by'] = UserData()->id;
            $uomConversion = UomConversion::create($data);
            DB::commit();
            return $uomConversion;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateUomConversion(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $uomConversion = UomConversion::find($id);
            $uomConversion->update($data);
            DB::commit();
            return $uomConversion;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMEssage($e->getMessage(), 402);
            throw $e;
        }
    }
}
