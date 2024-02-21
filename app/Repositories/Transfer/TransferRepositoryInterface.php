<?php

namespace App\Repositories\Transfer;

use Illuminate\Http\Request;

interface TransferRepositoryInterface
{

    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);

    public function transferConfirm(int $id);

}
