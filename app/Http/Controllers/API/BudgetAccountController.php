<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\BudgetAccount\BudgetAccountRepositoryInterface;

use App\Models\BudgetPriority;

class BudgetAccountController extends Controller
{
    //
    public function __construct(private BudgetAccountRepositoryInterface $repo)
    {

    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        ResponseData($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'budget_priority_id' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'main_account_type' => 'required',
            'main_account_id' => 'required',
            'sub_account_type' => 'required',
            'sub_account_id' => 'required',
        ]);

        $data = $request->all();
        $budgetAcc = $this->repo->create($data);
        ResponseData($budgetAcc, 201);
    }

    public function edit(Request $request, int $id)
    {
        $data = $request->all();
        $budgetAcc = $this->repo->update($id, $data);
        ResponseData($budgetAcc);
    }

    public function confirmBudgetAccount(Request $request, int $id)
    {
        $data = $request->all();
        $this->repo->confirm($id, $data);
    }

    public function getBudgetPriorities(Request $request)
    {
        ResponseData(BudgetPriority::all());
    }
}
