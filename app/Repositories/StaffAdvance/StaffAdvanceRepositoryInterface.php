<?php

namespace App\Repositories\StaffAdvance;

use Illuminate\Http\Request;

interface StaffAdvanceRepositoryInterface
{
    public function createStaffAdvance(Request $reqeust);
}
