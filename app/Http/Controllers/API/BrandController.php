<?php

namespace App\Http\Controllers\API;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    //
    public function index(Request $request)
    {
        $brandQuery = Brand::with('items')->orderBy('id', 'desc');
        if (isset($request->page)) {
            return ResponseData($brandQuery->paginate(config('common.list_count')));
        }
        return ResponseData($brandQuery->get());
    }

    public function createBrand(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $brand = Brand::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            if (isset($data['items'])) {
                $brand->items()->sync($data['items']);
            }
            DB::commit();
            ResponseData($brand);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getBrandByItem(Request $request)
    {
        $itemIds = $request->item_ids;
        $brands = Brand::whereHas('items', function ($query) use ($itemIds) {
            $query->whereIn('items.id', $itemIds);
        })->get();
        return ResponseData($brands);
    }
}
