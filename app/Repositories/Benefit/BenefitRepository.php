<?php

namespace App\Repositories\Benefit;

use Carbon\Carbon;
use App\Models\Advance;
use App\Models\Benefit;
use Illuminate\Http\Request;
use App\Models\AdvancePayment;
use App\Models\BenefitRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\BenefitListResource;
use App\Http\Resources\Mobile\StaffAdvanceResource;
use App\Http\Resources\Mobile\BenefitRequestResourceList;

class BenefitRepository implements BenefitInterface
{

    public function list($data)
    {
        $query = Benefit::orderBy('id', 'desc')->get();
        $benefits = BenefitListResource::collection($query);
        if (isset($data['page'])) {
            $perPage = $data['perPage'] ?? config('common.list_count');

            return paginateCollection($benefits, $perPage);
        }
        return $benefits;
    }

    public function updateOrCreateBenefit($request)
    {
        DB::beginTransaction(); // start transaction
        try {
            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            $data['created_by'] = \UserData()->id;
            
            $benefit = Benefit::updateOrCreate(['id' => $data['id']], $data);
            DB::commit();
            return $benefit;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function detailBenefit($id) {}

    public function requestBenefit($data) {}

    public function updateStatusBenefitRequest($data)
    {
        DB::beginTransaction(); // start transaction
        try {
            $benefitRequest = BenefitRequest::find($data['id']);
            if($benefitRequest->status=='confirmed' || $benefitRequest->status == 'cancelled'){
                \ResponseMessage('Status updated fail!',419); //status already updated 
            }
                $benefitRequest->status = $data['status'];
            if ($data['status'] == 'confirmed') { 
                $benefitRequest->confirmed_at = now();
                $benefitRequest->confirmed_by = \UserData()->id;
            }
            if ($data['status'] == 'cancelled') {
                $benefitRequest->cancelled_at = now();
                $benefitRequest->cancelled_by = \UserData()->id;
            }
            $benefitRequest->save();
            DB::commit();
            return $benefitRequest;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function getBenefitByType($type)
    {
        $benefit = Benefit::where('type', $type)->get();

        return BenefitListResource::collection($benefit);
    }

    public function createBenefitRequest($request)
    {
        $data=$request->all();
        DB::beginTransaction(); // start transaction
        try {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/staff_images');
                $imageUrl = Storage::url($path);
                $data['image'] = $imageUrl;
            }
            $benefitRequest = BenefitRequest::create($data);
            DB::commit();
            return $benefitRequest;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
    public function listBenefitRequest($data)
    {
        $benefitRequest = BenefitRequest::with(['benefit.menu'])->orderBy('id', 'desc')->get();
        if (isset($data['page'])) {
            $perPage = $data['perPage'] ?? config('common.list_count');

            return paginateCollection(BenefitRequestResourceList::collection($benefitRequest), $perPage);
        }
        return BenefitRequestResourceList::collection($benefitRequest);
    }
}
