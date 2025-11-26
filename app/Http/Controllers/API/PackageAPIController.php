<?php

namespace App\Http\Controllers\API;

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
        $packages = $this->packageRepo->listAllData($request);
    }

    public function createPackage(Request $request)
    {
        $packages = $this->packageRepo->createData($request->all());
    }

    public function editPackage(int $id,Request $request)
    {
        $packages = $this->packageRepo->editData($id, $request->all());
    }

    public function deletePackage(int $id)
    {
        $packages = $this->packageRepo->deleteData($id);
    }

    public function detailPackage(int $id)
    {
        $package = $this->packageRepo->detailPackage($id);
    }
}
