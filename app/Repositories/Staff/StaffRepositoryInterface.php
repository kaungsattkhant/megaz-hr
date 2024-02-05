<?php

namespace App\Repositories\Staff;

use App\Models\Staff;

interface StaffRepositoryInterface
{
    public function listAllData();

    public function createData(array $data);

    public function updateData(array $data,$id);

    public function deleteData(Staff $staff);


}
