<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Repositories\Package\PackageRepositoryInterface;
use Illuminate\Http\Request;

class PackageAPIController extends Controller
{
    //
    protected $packageRepo;

    public function __construct(PackageRepositoryInterface $packageRepo)
    {
        $this->packageRepo = $packageRepo;
    }

    public function getPackage(Request $request)
    {
        return $this->packageRepo->listAllPackage($request);
    }

}
