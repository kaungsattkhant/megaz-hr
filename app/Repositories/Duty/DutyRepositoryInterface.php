<?php

namespace App\Repositories\Duty;

use Illuminate\Http\Request;

interface DutyRepositoryInterface
{
    public function createDuty(Request $request);

    public function updateDuty(Request $request,int $id);

    public function listDuties(Request $request);

    public function deleteDuty(int $id);
}
