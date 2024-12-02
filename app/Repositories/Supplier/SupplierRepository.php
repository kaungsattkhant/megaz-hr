<?php

namespace App\Repositories\Supplier;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SupplierRepository implements SupplierInterface
{

    public function list($request)
    {
        $query = Supplier::with(['account', 'items'])->orderBy('id', 'DESC');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('shop_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('address', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('credit_limit', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('per_page') || $request->has('page')) {
            return $query->paginate(config('common.list_count'));
        }

        return $query->get();
    }


    public function updateOrCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $supplier = Supplier::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            $brandIds = $request->brands;// [1,2]
            $itemIds = $request->items;//[6]

            // foreach ($brandIds as $brandId) {
            //     // $supplier->items()->sync($request->items);
            //     $supplier->items()->syncWithPivotValues($itemIds, ['brand_id' => $brandId]);
            // }




            // if ($request->id) {
            //     $supplier->items()->detach(); // Detaches all relationships
            // }

            // $syncData = [];
            // foreach ($itemIds as $itemId) {
            //     foreach ($brandIds as $brandId) {
            //         $syncData[] = [
            //             'item_id' => $itemId,
            //             'brand_id' => $brandId,
            //         ];
            //     }
            // }
            // // Bulk insert into the pivot table
            // DB::table('supplier_item')->insert($syncData);

            foreach ($brandIds as $brandId) {
                foreach ($itemIds as $itemId) {
                    $supplier->items()->attach($itemId, ['brand_id' => $brandId]);
                }
            }

            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($supplier)
    {
        $supplier->items = $supplier->items;
        return $supplier;
    }
}
