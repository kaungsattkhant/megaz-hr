<?php

namespace App\Repositories\FixedAssetPurchase;

use App\Models\FixedAssetPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Provider\Time\FixedTimeProvider;

class FixedAssetPurchaseRepository implements FixedAssetPurchaseRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = FixedAssetPurchase::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $fixedAssetPurchase = FixedAssetPurchase::skip($skip)
                ->take($perPage)
                ->get();
            $fixedAssetPurchase = MakePaginationData($request, $totalCount, 'fixedAssetPurchase', $fixedAssetPurchase);
            return $fixedAssetPurchase;
        } else {
            $fixedAssetPurchase = FixedAssetPurchase::all();
            return $fixedAssetPurchase;
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['date'] = CurrentTime();
            $data['remaining_price'] = $data['total_price'];
            $data['remaining_duration'] = $data['total_duration'];
            $data['start_date'] = CurrentDate();
            $fixedAssetPurchase = FixedAssetPurchase::create($data);
            $fixedAssetPurchase->fixed_asset_id = sprintf('%05d', $fixedAssetPurchase->id);
            $fixedAssetPurchase->save();
            DB::commit();
            return $fixedAssetPurchase;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateIsCheck($request)
    {
        $staff = UserData();
        DB::beginTransaction();
        try {
            $fixedAssetPurchase = FixedAssetPurchase::find($request->id);
            if ($staff->hasRoles('Manager')) {
                $fixedAssetPurchase->manager_check_id = $staff->id;
                $fixedAssetPurchase->manager_check_time = CurrentTime();
            } elseif ($staff->hasRoles('MD')) {
                $fixedAssetPurchase->md_check_time = CurrentTime();
                $fixedAssetPurchase->is_md_checked = 1;
            } else {
                ResponseMessage('Login User is not valid');
            }
            $fixedAssetPurchase->save();
            DB::commit();
            ResponseMessage('Updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
