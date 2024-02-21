<?php

namespace App\Repositories\Staff;

use Illuminate\Http\Request;

use App\Models\Staff;

interface StaffRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData($id);

    public function getStaffByDepartment(Request $request, int $departmentId);
}
