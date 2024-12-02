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
        return ResponseData(Brand::orderBy('id','desc')->get());
    }

    public function createBrand(Request $request){
        $data=$request->all();
        DB::beginTransaction();
        try {
            $brand = Brand::create($data);
            DB::commit();
            return $brand;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getBrandByItem(Request $request)
    {
        $itemIds=$request->item_ids;
        $brands=Brand::whereHas('items',function($query)use($itemIds){
            $query->whereIn('item_id',$itemIds);
        })->get();
        return ResponseData($brands);
    }

}
