<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Pack\PackRepositoryInterface;
use Illuminate\Http\Request;

class PackAPIController extends Controller
{
    //
    protected $packRepo;
    public function __construct(PackRepositoryInterface $packRepo)
    {
        $this->packRepo = $packRepo;
    }

    public function createPack(Request $request)
    {
        $pack = $this->packRepo->createPack($request->all());
        ResponseData($pack);
    }
}
