<?php

namespace App\Repositories\Contract;

use App\Models\Contract;
use Illuminate\Support\Facades\DB;

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
            $contract->contract_staff()->syncWithoutDetaching($data['staff_ids']);
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
        return Contract::with(['role', 'contract_category', 'company_authorizer', 'witness'])->orderBy('id', 'desc')->get();
    }
}
