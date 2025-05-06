<?php

namespace App\Repositories\Uom;

use App\Models\Uom;
use Illuminate\Http\Request;
use App\Models\UomConversion;
use Illuminate\Support\Facades\DB;

class UomRepository implements UomRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $uoms = Uom::orderBy('created_at', 'desc')->paginate(config('common.list_count'));
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
            $data['uom_code'] = $data['uom_code'];
            $uom = Uom::firstOrCreate(['name' => $data['name'], 'uom_code' => $data['uom_code']], $data);
            if ($uom) {
                UomConversion::firstOrCreate(
                    [
                        'base_unit_id' => $uom->id,
                        'conversion_unit_id' => $uom->id,
                        'conversion' => 1,
                    ],
                    [
                        'base_unit_id' => $uom->id,
                        'conversion_unit_id' => $uom->id,
                        'conversion' => 1,
                        'created_by' => UserData()->id,
                        'is_show' => 0
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
            if (!$uom) {
                ResponseMessage("Uom not found", 404);
            }

            $uom->update([
                'name' => $data['name'],
                'uom_code' => $data['uom_code'],
            ]);
            DB::commit();
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

    // uom conversion

    public function uomConversaionList(Request $request)
    {
        $uomsQuery = UomConversion::with('baseUnit', 'conversionUnit')
            ->orderBy('created_at', 'desc')
            ->where('is_show', 1);

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $uomsQuery->where(function ($query) use ($searchTerm) {
                $query->where('conversion', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('baseUnit', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('conversionUnit', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        if ($request->per_page || $request->page) {
            return $uomsQuery->paginate(config('common.list_count'));
        } else {
            return $uomsQuery->get();
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
