<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\StaffAdvance\StaffAdvanceRepositoryInterface;
use Illuminate\Http\Request;

class StaffAdvanceAPIController extends Controller
{
    //
    protected $staffAdvRepo;
    public function __construct(StaffAdvanceRepositoryInterface $staffAdvRepo)
    {
        $this->staffAdvRepo = $staffAdvRepo;
    }

    public function createStaffAdvance(Request $request)
    {
        $this->staffAdvRepo->createStaffAdvance($request);
    }
}
