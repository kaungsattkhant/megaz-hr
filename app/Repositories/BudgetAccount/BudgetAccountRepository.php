<?php

namespace App\Repositories\BudgetAccount;

use Illuminate\Http\Request;

use App\Models\BudgetAccount;

class BudgetAccountRepository implements BudgetAccountRepositoryInterface
{
    public function list(Request $request)
    {
        if($request->page){
            return BudgetAccount::with([
                'budgetPriority',
                'mainAccount',
                'subAccount'
            ])->orderBy('date')->paginate(config('common.list_count'));
        }else{
            return BudgetAccount::with([
                'budgetPriority',
                'mainAccount',
                'subAccount'
            ])->orderBy('date')->get();
        }
    }

    public function create(array $data)
    {
        $budgetAccount = BudgetAccount::create($data);
        $budgetAccount->load(['budgetPriority','mainAccount','subAccount']);
        return $budgetAccount;
    }

    public function update(int $id, array $data)
    {
        $budgetAccount = BudgetAccount::find($id);
        $budgetAccount->update($data);
        $budgetAccount->load(['budgetPriority','mainAccount','subAccount']);
        return $budgetAccount;
    }

    public function confirm(int $id)
    {
        $budgetAccount = BudgetAccount::find($id);
        $budgetAccount->status = 'confirmed';
        $budgetAccount->save();
        ResponseMessage("Budget account confirmed successfully");
    }
}
