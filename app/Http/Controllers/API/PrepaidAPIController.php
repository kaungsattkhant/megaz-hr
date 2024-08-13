<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Prepaid\PrepaidRepositoryInterface;
use Illuminate\Http\Request;

class PrepaidAPIController extends Controller
{
    //
    protected $prepaidRepo;
    public function __construct(PrepaidRepositoryInterface $prepaidRepo)
    {
        $this->prepaidRepo = $prepaidRepo;
    }

    public function createPrepaid(Request $request)
    {
        $this->prepaidRepo->createPrepaid($request);
    }
}
