<?php

namespace App\Repositories\Duty;

use Illuminate\Http\Request;

interface DutyRepositoryInterface
{
    public function createDuty(Request $request);

    public function listDuties(Request $request);
}
