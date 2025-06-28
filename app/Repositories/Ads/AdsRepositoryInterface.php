<?php

namespace App\Repositories\Ads;

use Illuminate\Http\Request;

interface AdsRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function editData(array $data, int $id);

    public function deleteData(int $id);

    // user app
    public function listAdsByUserApp(Request $request);

    public function adsDetail(int $id);

    public function latestAds();
}
