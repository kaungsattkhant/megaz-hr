<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractListResource;
use App\Models\Contract;
use App\Repositories\Contract\ContractRepositoryInterface;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    private ContractRepositoryInterface $contractRepo;

    public function __construct(ContractRepositoryInterface $contractRepo)
    {
        $this->contractRepo = $contractRepo;
    }

    public function index(Request $request)
    {
        $contracts = $this->contractRepo->list($request);
        ResponseData($contracts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:orientation,occasional',
            'company_authorizer_id' => 'required|exists:staff,id',
            'witness_id' => 'required|exists:staff,id',
            'text' => 'nullable|string',
            'contract_category_id' => 'required|exists:contract_categories,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        $contract = $this->contractRepo->updateOrCreate($request->all());
        ResponseData($contract);
    }

    public function show(Contract $contract)
    {
        $contract = $this->contractRepo->detail($contract);
        ResponseData($contract);
    }

    public function addStaffToContract(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:staff,id',
        ]);

        $this->contractRepo->addStaffToContract($request->all());
        

        ResponseMessage('Staff added to contract successfully');
    }

    //staff
    public function getContractList(){
        $contracts=$this->contractRepo->getContractList();
        return ContractListResource::collection($contracts);
    }
}
