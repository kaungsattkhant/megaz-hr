<?php

namespace App\Repositories;

use App\Models\Staff;

interface AreaRepositoryInterface
{
    public function getAreasByStaff(Staff $staff);
}
