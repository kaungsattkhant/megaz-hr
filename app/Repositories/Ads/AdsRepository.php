<?php

namespace App\Repositories\Ads;

use App\Models\Ads;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdsRepository implements AdsRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $ads = Ads::paginate(config('common.list_count'));
        ResponseData($ads);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            if ($data['image']) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images/ads', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }
            $ads = Ads::create($data);
            DB::commit();
            ResponseData($ads);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function editData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $ads = Ads::find($id);
            if ($ads) {
                if ($data['image']) {
                    $imageData = $data['image'];
                    $extension = $imageData->getClientOriginalExtension();
                    $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                    $data['image_path'] = $imageData->storeAs('images/ads', $hashedName, 'public');
                    $data['image_url'] = Storage::url($data['image_path']);
                }
                $ads->update($data);
                DB::commit();
                ResponseData($ads);
            } else {
                ResponseMessage('No ads found with given id', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        DB::beginTransaction();
        try {
            $ads = Ads::find($id);
            if ($ads) {
                $ads->delete();
                DB::commit();
                ResponseMessage('Ads deleted successfully', 200);
            } else {
                ResponseMessage('No ads found with given id', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    // user app
    public function listAdsByUserApp(Request $request)
    {
        $adsQuery = Ads::query();
        $adsQuery->orderBy('created_at','desc');
        if($request->type)
        {
            $adsQuery->where('type',$request->type);
        }
        $ads = $adsQuery->get();
        ResponseData($ads);
    }
}
