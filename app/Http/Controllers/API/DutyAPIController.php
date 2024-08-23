<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Duty\DutyRepositoryInterface;
use Illuminate\Http\Request;

class DutyAPIController extends Controller
{
    //
    protected $dutyRepo;
    public function __construct(DutyRepositoryInterface $dutyRepo)
    {
        $this->dutyRepo = $dutyRepo;
    }

    public function createDuty(Request $request)
    {
        $this->dutyRepo->createDuty($request);
    }
}
