<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Ads\AdsRepository;
use Illuminate\Http\Request;

class AdsAPIController extends Controller
{
    //
    protected $adsRepo;
    public function __construct(AdsRepository $adsRepo)
    {
        $this->adsRepo = $adsRepo;
    }

    public function getAds(Request $request)
    {
        $ads = $this->adsRepo->listAllData($request);
    }

    public function createAds(Request $request)
    {
        $ads = $this->adsRepo->createData($request->all());
    }
    public function adsDetail(int $id)
    {
        $ads = $this->adsRepo->adsDetail($id);
    }

    public function editAds(Request $request, int $id)
    {
        $ads = $this->adsRepo->editData($request->all(),$id);
    }

    public function deleteAds(int $id)
    {
        $ads = $this->adsRepo->deleteData($id);
    }

    public function latestAds()
    {
        $ads = $this->adsRepo->latestAds();
    }
}
