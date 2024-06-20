<?php

namespace App\Http\Controllers\API\Customer;

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

    public function getAdsByUserApp(Request $request)
    {
        $ads = $this->adsRepo->listAdsByUserApp($request);
    }
}
