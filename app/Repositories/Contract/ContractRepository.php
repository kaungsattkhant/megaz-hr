<?php

namespace App\Repositories\Contract;

use App\Enums\ContractStaffEnum;
use App\Enums\ContractTypeEnum;
use App\Enums\StaffStatus;
use App\Models\Contract;
use App\Models\ContractStaff;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContractRepository implements ContractRepositoryInterface
{
    public function list($request)
    {
        $query = Contract::with('contract_category')->orderBy('id', 'DESC');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('per_page') || $request->has('page')) {
            return $query->paginate(config('common.list_count'));
        }

        return $query->get();
    }

    public function updateOrCreate(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            $contract = Contract::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            if ($data['type'] === ContractTypeEnum::ORIENTATION->value) {
                $staffArr = Staff::whereIn('status', [StaffStatus::PROBATION->value, StaffStatus::PERMANENT->value])->pluck('id')->toArray();
                foreach ($staffArr as $key => $staffId) {
                    $this->exitContractStaff($contract, $staffId);
                    $contract->contract_staff()->create(['staff_id' => $staffId]);
                }
            }
            DB::commit();
            return $contract;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function detail($contract)
    {
        $contract->contractCategory = $contract->contractCategory;
        return $contract;
    }

    public function addStaffToContract(array $data)
    {
        DB::beginTransaction();
        try {
            $contract = Contract::findOrFail($data['contract_id']);
            if ($contract->type === ContractTypeEnum::ORIENTATION->value) {
                \ResponseMessage('Only Occasional contract can add staff', 422);
            }
            foreach($data['staff_ids'] as $staffId) {
                $this->exitContractStaff($contract, $staffId);
                $contract->contract_staff()->create(['staff_id' => $staffId]);
            }
            // $contract->contract_staff()->syncWithoutDetaching($data['staff_ids']);
            DB::commit();
            return $contract;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    //staff
    public function getContractList()
    {
        return ContractStaff::where('staff_id', \UserData()->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function signedContract(array $data)
    {
        DB::beginTransaction();
        try {
            $contractStaff = ContractStaff::findOrFail($data['id']);
            if (isset($data['signed_document'])) {
                $image = $data['signed_document'];
                $extension = $image->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $path = $image->storeAs("staff_contracts/{$contractStaff->id}", $hashedName, 'public');
                $signed_document = Storage::url($path);
            }
            $contractStaff->signed_document = $signed_document ?? null;
            $contractStaff->signed_at = now();
            $contractStaff->status = ContractStaffEnum::SIGNED->value;
            $contractStaff->save();
            DB::commit();
            return $contractStaff;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
    public function exitContractStaff($contract,$staffId){
        $exists = $contract->contract_staff()
            ->where('staff_id', $staffId)
            ->exists();

        if ($exists) {
            $staff=Staff::find($staffId);
            ResponseMessage("{$staff->name} already attached to this contract", 400);
        }
    }
}
