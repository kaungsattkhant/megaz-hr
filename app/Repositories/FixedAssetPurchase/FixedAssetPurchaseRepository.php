<?php

namespace App\Repositories\FixedAssetPurchase;

use App\Models\FixedAssetPurchase;
use Illuminate\Http\Request;

class FixedAssetPurchaseRepository implements FixedAssetPurchaseRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = FixedAssetPurchase::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $fixedAssetPurchase = FixedAssetPurchase::skip($skip)
                ->take($perPage)
                ->get();
            $fixedAssetPurchase = MakePaginationData($request, $totalCount, 'fixedAssetPurchase', $fixedAssetPurchase);
            return $fixedAssetPurchase;
        }
        else{
            $fixedAssetPurchase = FixedAssetPurchase::all();
            return $fixedAssetPurchase;
        }
    }

    public function createData(array $data)
    {
        $data['date'] = CurrentTime();
        $data['remaining_price'] = $data['total_price'];
        $data['remaining_duration'] = $data['total_duration'];
        $data['start_date'] = CurrentDate();
        $fixedAssetPurchase = FixedAssetPurchase::create($data);
    }
}
