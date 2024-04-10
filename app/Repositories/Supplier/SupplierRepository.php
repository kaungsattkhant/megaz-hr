<?php

namespace App\Repositories\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;


class SupplierRepository implements SupplierInterface
{

    public function list($request){
        if($request->per_page || $request->page){
            return Supplier::with(['items'])->orderBy('id','DESC')->paginate(config('common.list_count'));
        }
        return Supplier::with(['items'])->orderBy('id', 'ASC')->get();
    }

    public function updateOrCreate($request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $supplier=Supplier::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            $supplier->items()->sync($request->items);
            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($supplier){
        $supplier->items=$supplier->items;
        return $supplier;
    }
}
