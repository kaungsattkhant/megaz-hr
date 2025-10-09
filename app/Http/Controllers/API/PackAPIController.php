<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePackRequest;
use App\Http\Requests\PackCreateRequest;
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

    public function getPacksData(Request $request)
    {
        $packs = $this->packRepo->listAllData($request);
        ResponseData($packs);
    }

    public function createPack(PackCreateRequest $request)
    {
        $pack = $this->packRepo->createPack($request->all());
        ResponseData($pack);
    }

    public function changePack(ChangePackRequest $request)
    {
        dd('abc');
        $pack = $this->packRepo->changePack($request);
        ResponseData($pack);
    }
}
