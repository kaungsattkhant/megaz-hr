<?php

namespace App\Repositories\Complaint;

use Illuminate\Http\Request;

interface ComplaintRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function statusChange(string $data,int $id);

    public function deleteData($id);


}
