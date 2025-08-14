<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AssetItemEquipmentAssign\AssetItemEquipmentAssignRepositoryInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class AssetItemEquipmentAssignController extends Controller
{
    private AssetItemEquipmentAssignRepositoryInterface $assetItemEquipmentAssignRepo;

    public function __construct(AssetItemEquipmentAssignRepositoryInterface $assetItemEquipmentAssignRepo)
    {
        $this->assetItemEquipmentAssignRepo = $assetItemEquipmentAssignRepo;
    }
    public function getAssetAssigns(Request $request)
    {
        $assetAssigns = $this->assetItemEquipmentAssignRepo->getAssetAssigns($request);
        ResponseData($assetAssigns);
    }

    public function createAssetAssign(Request $request)
    {
        $assetAssign = $this->assetItemEquipmentAssignRepo->createAssetAssign($request->all());
        ResponseData($assetAssign, 201);
    }

    public function createEquipmentAssign(Request $request)
    {
        $equipmentAssign = $this->assetItemEquipmentAssignRepo->createEquipmentAssign($request->all());
        ResponseData($equipmentAssign, 201);
    }

    public function getEquipmentAssigns()
    {
        $equipmentAssigns = $this->assetItemEquipmentAssignRepo->getEquipmentAssigns();
        ResponseData($equipmentAssigns);
    }
}
