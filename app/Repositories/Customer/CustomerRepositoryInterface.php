<?php

namespace App\Repositories\Customer;

use Illuminate\Http\Request;

interface CustomerRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

}
