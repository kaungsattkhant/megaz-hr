<?php

namespace App\Repositories\BudgetAccount;

use Illuminate\Http\Request;

interface BudgetAccountRepositoryInterface
{
    public function list(Request $request);

    public function create(array $data);

    public function update(int $id, array $data);

    public function confirm(int $id);
}
