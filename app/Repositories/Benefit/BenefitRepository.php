<?php

namespace App\Repositories\Benefit;

use App\Http\Resources\Admin\BenefitListResource;
use App\Http\Resources\Mobile\BenefitRequestResourceList;
use Carbon\Carbon;
use App\Models\Advance;
use App\Models\Benefit;
use Illuminate\Http\Request;
use App\Models\AdvancePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Mobile\StaffAdvanceResource;
use App\Models\BenefitRequest;

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

    public function updateOrCreateBenefit($data)
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

    public function updateStatusBenefit($data) {}

    public function getBenefitByType($type)
    {
        $benefit = Benefit::where('type', $type)->get();

        return BenefitListResource::collection($benefit);
    }

    public function createBenefitRequest($data) {
        DB::beginTransaction(); // start transaction
        try {
            $benefitRequest = BenefitRequest::create($data);
            DB::commit();
            return $benefitRequest;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
    public function listBenefitRequest($data){
        $benefitRequest=BenefitRequest::with(['benefit.menu'])->orderBy('id','desc')->get();
        return BenefitRequestResourceList::collection($benefitRequest);
    }
}
